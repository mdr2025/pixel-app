<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class SystemResettingExcludedTablesFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
     

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "system-resetting-excluded-tables";
    } 
   
}