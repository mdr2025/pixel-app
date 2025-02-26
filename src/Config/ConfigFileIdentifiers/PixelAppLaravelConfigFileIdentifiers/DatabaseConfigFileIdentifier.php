<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers;
 
class DatabaseConfigFileIdentifier extends PixelAppLaravelConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "database";
    } 
}