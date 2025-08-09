<?php

namespace PixelApp\Jobs\TenantCompanyJobs\ExpImpManagementHandlingJobs;

use Exception;
use ExpImpManagement\ExportersManagement\Jobs\OldDataExportersDeleterJob;
use Illuminate\Support\Collection;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\CompanyModule\TenantCompany;

class OldDataExportersDeleterAltJob extends OldDataExportersDeleterJob
{
  
    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(
            PixelAppBootingManager::isBootingForAdminPanelApp() 
            ||
            PixelAppBootingManager::isBootingForNormalApp())
        {
            $this->processDeleting();
            return ;
        }

        $this->proccessDeletingForTenants();
       
    }


    protected function proccessDeletingForTenants() : void
    {
        /** @var TenantCompany $tenant */
        foreach($this->fetchTenants() as $tenant)
        {
            $tenant->run(function()
            {
                $this->processDeleting();
            });
        }
    }

    protected function fetchTenantsFromCentralSide() : Collection
    {
        return PixelTenancyManager::fetchTenantsFromCentralSide();
    }

    protected function fetchTenantsByAdminPanel()  : Collection
    {
        return PixelTenancyManager::fetchTenantsByAdminPanel();
    }

    protected function fetchTenants() 
    {
        if(PixelAppBootingManager::isBootingForTenantApp())
        {
            return $this->fetchTenantsByAdminPanel();
        }

        if(PixelAppBootingManager::isBootingForMonolithTenancyApp())
        {
            return $this->fetchTenantsFromCentralSide();
        }

        return collect([]);
    }
}
