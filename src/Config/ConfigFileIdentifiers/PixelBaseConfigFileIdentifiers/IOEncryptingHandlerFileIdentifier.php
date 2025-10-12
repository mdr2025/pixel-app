<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class IOEncryptingHandlerFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
  

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "io-encryption-handler";
    } 
   
}