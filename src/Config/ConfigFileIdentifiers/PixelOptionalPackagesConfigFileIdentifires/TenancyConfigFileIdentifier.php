<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelOptionalPackagesConfigFileIdentifires;

use PixelApp\Config\ConfigFileIdentifiers\Interfaces\ReplacableConfigFileIdentifier;

class TenancyConfigFileIdentifier 
      extends PixelOptionalPackagesConfigFileIdentifire
      implements ReplacableConfigFileIdentifier
{  
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "tenancy";
    } 
}