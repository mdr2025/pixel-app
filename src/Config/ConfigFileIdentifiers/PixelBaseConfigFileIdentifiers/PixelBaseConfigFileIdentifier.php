<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;

use MergingOnNeedConditions;
use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\MergableConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\PublishableConfigFileIdentifier;

abstract class PixelBaseConfigFileIdentifier 
         extends ConfigFileIdentifier
         implements MergableConfigFileIdentifier , PublishableConfigFileIdentifier
{
    use MergingOnNeedConditions;

    public function getConfigPublishGroupingKeyNames() : array
    {
        return [ "pixel-base-configs" ];
    }
    
    public function getConfigKeyName() : string
    {
        return $this->getPackageConfigsFolderName() . "." . $this->getFileName();
    }
    
    public function getPackageConfigsFolderName() : string
    {
        return "PixelAppConfigs";
    }
    
    public function getPixelAppConfigPackagePath() : string
    {
        return $this->getPackageConfigBasePath() . "/" . $this->getPackageConfigsFolderName();
    }

    public function getFileProjectRelevantPath() : string
    {
        return static::getPackageConfigsFolderName() 
               . "/" .
               $this->getFileName() . $this->getFileExtension();
    }
}