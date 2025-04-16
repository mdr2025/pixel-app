<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class PixelAppACLConfigFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
    public function getConfigPublishGroupingKeyNames() : array
    {
        $groups = parent::getConfigPublishGroupingKeyNames();
        $groups[] = "pixel-app-package-acl";
        return $groups;
    }
    
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "acl";
    }
 
}