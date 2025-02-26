<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers;
 
class QueueConfigFileIdentifier extends PixelAppLaravelConfigFileIdentifier
{ 
    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "queue";
    } 
}