<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers;
 
class AuthConfigFileIdentifier extends PixelAppLaravelConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "auth";
    } 
}