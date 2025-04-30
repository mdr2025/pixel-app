<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class SystemResettingExcludedSeedingTablesFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
    
    public function getConfigPublishGroupingKeyNames() : array
    {
        $groups = parent::getConfigPublishGroupingKeyNames();
        $groups[] = "system-resetting-excluded-seeding-tables";
        return $groups;
    }

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "system-resetting-excluded-seeding-tables";
    } 
   
}