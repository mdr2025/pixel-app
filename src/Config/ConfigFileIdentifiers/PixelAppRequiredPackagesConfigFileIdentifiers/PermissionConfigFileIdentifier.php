<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers;
 

class PermissionConfigFileIdentifier extends PixelAppRequiredPackagesConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "permission";
    }
    
    public function getConfigPublishGroupingKeyNames() : array
    {
        return [ "pixel-permission-config" ];
    } 
 
}