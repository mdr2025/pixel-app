<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers;

use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\ReplacableConfigFileIdentifier;

abstract class PixelAppLaravelConfigFileIdentifier 
         extends ConfigFileIdentifier
         implements ReplacableConfigFileIdentifier
{ 
     
    protected function getPackageConfigsFolderName() : string
    {
        return "LaravelConfigFiles";
    }
    
    protected function getFilePackageConfigRelevantPath() : string
    {
        return $this->getPackageConfigsFolderName() 
        . "/" .
        $this->getFileName(). $this->getFileExtension();
    }
}