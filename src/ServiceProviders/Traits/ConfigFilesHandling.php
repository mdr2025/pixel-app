<?php

namespace PixelApp\ServiceProviders\Traits;

use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\MergableConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\PublishableConfigFileIdentifier;
use PixelApp\Config\PixelConfigManager;

trait ConfigFilesHandling
{  
  
    protected function mergeConfigFiles() : void
    { 
        foreach($this->getMergableConfigFileIdentifiers() as $identifierClass)
        {
            $this->mergeConfigFile($identifierClass);
        } 
    }

    protected function prepareConfigFilesPublishing() : void
    {
        foreach($this->getPublishableConfigFileIdentifiers() as $identifierClass)
        {
            $this->prepareConfigFilePublishing($identifierClass);
        } 
    }

    protected function initConfigFileIdentifier(string $identifierClass) : ConfigFileIdentifier|MergableConfigFileIdentifier|PublishableConfigFileIdentifier
    {
        return $identifierClass::Singleton();
    }

    protected function mergeConfigFile(string $identifierClass) : void
    {
        $identifier = $this->initConfigFileIdentifier($identifierClass);
        if($identifier->DoesItNeedToMerge())
        {
            $this->mergeConfigFrom(
                $identifier->getFilePath(),
                $identifier->getConfigKeyName()
            );
        }
    }
    protected function getMergableConfigFileIdentifiers() : array
    {
        return array_filter(PixelConfigManager::getMergableConfigFileIdentifiers() , function($class)
        {
            return is_subclass_of($class , ConfigFileIdentifier::class)
                   &&
                   in_array(MergableConfigFileIdentifier::class , class_implements($class) );
        });
    }

    protected function prepareConfigFilePublishing(string $identifierClass) : void
    {
        $identifier = $this->initConfigFileIdentifier($identifierClass);
        $this->publishes([
            $identifier->getFilePath()
            => 
            config_path( $identifier->getFileProjectRelevantPath() ),
        ], $identifier->getConfigPublishGroupingKeyNames());
 
    }
    protected function getPublishableConfigFileIdentifiers() : array
    {
        return array_filter(PixelConfigManager::getPublishableConfigFileIdentifiers() , function($class)
        {
            return is_subclass_of($class , ConfigFileIdentifier::class)
                   &&
                   in_array(PublishableConfigFileIdentifier::class , class_implements($class) );
        });
    } 
}