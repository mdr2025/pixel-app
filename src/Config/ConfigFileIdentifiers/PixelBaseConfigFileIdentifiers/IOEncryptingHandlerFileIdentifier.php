<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class IOEncryptingHandlerFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
    
    public function getConfigPublishGroupingKeyNames() : array
    {
        $groups = parent::getConfigPublishGroupingKeyNames();
        $groups[] = "io-encryption-handler";
        return $groups;
    }

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "io-encryption-handler";
    } 
   
}