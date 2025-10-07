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
            /**
             * @todo using it after upgrading package to laravel 12 to be compatible with ExpImpManagement package
             */
            //$this->processDeleting();
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
                /**
                 * @todo using it after upgrading package to laravel 12 to be compatible with ExpImpManagement package
                 */
                //$this->processDeleting();
            });
        }
    }

    protected function fetchApprovedTenantsFromCentralSide() : Collection
    {
        return PixelTenancyManager::fetchApprovedTenantsFromCentralSide();
    }

    protected function fetchApprovedTenantsByAdminPanel()  : Collection
    {
        return PixelTenancyManager::fetchApprovedTenantsByAdminPanel();
    }

    protected function fetchTenants() 
    {
        if(PixelAppBootingManager::isBootingForTenantApp())
        {
            return $this->fetchApprovedTenantsByAdminPanel();
        }

        if(PixelAppBootingManager::isBootingForMonolithTenancyApp())
        {
            return $this->fetchApprovedTenantsFromCentralSide();
        }

        return collect([]);
    }
}
