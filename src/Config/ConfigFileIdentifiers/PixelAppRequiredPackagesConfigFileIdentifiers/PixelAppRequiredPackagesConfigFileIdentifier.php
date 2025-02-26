<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers;
 
use MergingOnNeedConditions;
use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\MergableConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\Interfaces\PublishableConfigFileIdentifier;

abstract class PixelAppRequiredPackagesConfigFileIdentifier 
         extends ConfigFileIdentifier
         implements MergableConfigFileIdentifier , PublishableConfigFileIdentifier
{  
    use MergingOnNeedConditions;
 
    protected function getPackageConfigsFolderName() : string
    {
        return "RequiredPackagesConfigs";
    }
    protected function getFilePackageConfigRelevantPath() : string
    {
        return $this->getPackageConfigsFolderName() 
               . "/" .
               $this->getFileName(). $this->getFileExtension();
    }
}