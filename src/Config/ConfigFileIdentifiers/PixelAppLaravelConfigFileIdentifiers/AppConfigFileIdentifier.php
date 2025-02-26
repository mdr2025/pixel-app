<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers;
 
class AppConfigFileIdentifier extends PixelAppLaravelConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "app";
    } 
 
}