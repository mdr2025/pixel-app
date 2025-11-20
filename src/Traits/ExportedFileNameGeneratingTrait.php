<?php

namespace PixelApp\Traits;


use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\Models\CompanyModule\TenantCompany;

trait ExportedFileNameGeneratingTrait
{
       /**
     * Generate a file name with tenant abbreviation or name prefix.
     *
     * @param string $fileName
     * @return string
     */
    public function handleTenantFileName(string $fileName): string
    {
        if($this->isBootingForTenantInitializerApp() && $tenant =  tenant())
        {
            return $this->composeTenantFileName($tenant , $fileName);
        }
 
        return $fileName; 
    }

    protected function isBootingForTenantInitializerApp() : bool
    {
        return PixelAppBootingManager::isBootingForTenantApp() 
               ||
               PixelAppBootingManager::isBootingForMonolithTenancyApp();
    }

    protected function composeTenantFileName(TenantCompany $tenant , string $fileName) : string
    {
        return ($this->getTenantAbbreviation($tenant ) ?? $this->composeUsableTenantName($tenant )) . $fileName;
    }

    protected function getTenantAbbreviation(TenantCompany $tenant) : ?string
    {
        return $tenant->abbreviation ;
    }

    protected function composeUsableTenantName(TenantCompany $tenant) : string
    {
        return str_word_count($tenant->name) > 1
               ? strtok($tenant->name , ' ')
               : $tenant->name;
    }
}
