<?php

namespace PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers;
 
class ExcludedTenantsSeedingTablesFileIdentifier extends PixelBaseConfigFileIdentifier
{ 
    
    public function getConfigPublishGroupingKeyNames() : array
    {
        $groups = parent::getConfigPublishGroupingKeyNames();
        $groups[] = "excluded-tenants-seeding-tables";
        return $groups;
    }

    /**
     *  file name without extension
     */
    public function getFileName() : string
    {
        return "excluded-tenants-seeding-tables";
    } 
   
}