<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class PixelAppACLConfigFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
   
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "acl";
    }
 
}