<?php

namespace PixelApp\Config;
 
use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier; 
use PixelApp\CustomLibs\PixelAppStubsManager\PixelAppStubsManager;
use PixelApp\CustomLibs\PixelAppStubsManager\StubIdentifiers\StubIdentifier;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;

class PixelConfigStubsManager extends PixelAppStubsManager
{ 
 
    public function getTenancyConfigFileIdentifierClass() : string
    {
        return PixelConfigManager::getTenancyConfigFileIdentifierClass(); 
    }
 
    public function getPixelAppLaravelConfigFileIdentifierClasses() : array
    {
        return PixelConfigManager::getPixelAppLaravelConfigFileIdentifierClasses();
    }
   
    protected function  initStubIdentifier(string $stubPath , string $newPath) : StubIdentifier
    {
        return StubIdentifier::create($stubPath , $newPath);
    }
 
    protected function composeConfigFileProjectConfigPath(ConfigFileIdentifier $configFileIdentifier) : string
    {
        $fileProjectRelevantPath = $configFileIdentifier->getFileProjectRelevantPath();
        return config_path($fileProjectRelevantPath);
    }
    
    protected function initConfigFileIdentifier(string $identifierClass) : ConfigFileIdentifier
    { 
        return $identifierClass::Singleton();
    }

    protected function replaceConfigFile(string $configFileIdentifierClass) : void
    {
        $configFileIdentifier = $this->initConfigFileIdentifier($configFileIdentifierClass);

        $filePackagePath = $configFileIdentifier->getFilePath();
        $fileProjectConfigPath = $this->composeConfigFileProjectConfigPath($configFileIdentifier);

        $stubIdentifier = $this->initStubIdentifier($filePackagePath , $fileProjectConfigPath); 
        $this->replaceStubFile($stubIdentifier);
    }

    protected function replaceTenancyConfigFile() : void
    {
        $this->replaceConfigFile( $this->getTenancyConfigFileIdentifierClass() );
    }

    protected function replaceLaravelConfigFiles()
    {
        foreach($this->getPixelAppLaravelConfigFileIdentifierClasses() as $identifierClass)
        {
            $this->replaceConfigFile($identifierClass);
        }
    }

    public function replacePackageConfigFiles() : void
    {
        $this->replaceLaravelConfigFiles();

        if(PixelTenancyManager::isItTenancySupportyerApp())
        {
            $this->replaceTenancyConfigFile();
        }
    }
 
}