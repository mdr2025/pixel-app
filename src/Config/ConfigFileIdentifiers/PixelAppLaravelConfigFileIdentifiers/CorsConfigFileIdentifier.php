<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers;
 
class CorsConfigFileIdentifier extends PixelAppLaravelConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "cors";
    } 
}