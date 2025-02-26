<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers;
 

class AuthorizationManagementConfigFileIdentifier extends PixelAppRequiredPackagesConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "authorization-management-config";
    }
 
    public function getConfigPublishGroupingKeyNames() : array
    {
        return [ "pixel-authorization-management-config" ];
    }
 
}