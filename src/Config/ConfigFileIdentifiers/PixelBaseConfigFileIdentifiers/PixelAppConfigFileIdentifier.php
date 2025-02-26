<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class PixelAppConfigFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
    
    public function getConfigPublishGroupingKeyNames() : array
    {
        $groups = parent::getConfigPublishGroupingKeyNames();
        $groups[] = "pixel-app-package-config";
        return $groups;
    }

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "pixel-app-package-config";
    } 
   
}