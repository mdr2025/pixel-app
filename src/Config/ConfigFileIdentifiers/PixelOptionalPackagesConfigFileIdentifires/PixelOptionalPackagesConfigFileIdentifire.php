<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelOptionalPackagesConfigFileIdentifires;

use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;

abstract class PixelOptionalPackagesConfigFileIdentifire extends ConfigFileIdentifier
{  

    protected function getPackageConfigsFolderName() : string
    {
        return "OptionalPackagesConfigs";
    }

    protected function getFilePackageConfigRelevantPath() : string
    {
        return $this->getPackageConfigsFolderName() 
               . "/" .
               $this->getFileName(). $this->getFileExtension();
    }
}