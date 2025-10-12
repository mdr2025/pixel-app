<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class SystemResettingExcludedSeedingTablesFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
     

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "system-resetting-excluded-seeding-tables";
    } 
   
}