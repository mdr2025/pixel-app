<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers;
 
class PassportConfigFileIdentifier extends PixelAppRequiredPackagesConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "passport";
    }
    
    public function getConfigPublishGroupingKeyNames() : array
    {
        return [ "pixel-passport-config" ];
    }
}