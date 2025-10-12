<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;

use PixelApp\Config\ConfigFileIdentifiers\Traits\MergingOnNeedConditions;
use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\MergableConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\PublishableConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\ReplacableConfigFileIdentifier;

abstract class PixelBaseConfigFileIdentifier 
         extends ConfigFileIdentifier
         implements ReplacableConfigFileIdentifier
{
  
    public function getPackageConfigsFolderName() : string
    {
        return "PixelAppConfigs";
    }
    
    protected function getFilePackageConfigRelevantPath() : string
    {
        return $this->getPackageConfigsFolderName() 
               . "/" .
               $this->getFileName(). $this->getFileExtension();
    }
 
}