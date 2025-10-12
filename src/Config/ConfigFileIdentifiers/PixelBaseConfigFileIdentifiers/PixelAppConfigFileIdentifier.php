<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class PixelAppConfigFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
       
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "pixel-app-package-config";
    } 
   
}