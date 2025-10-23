<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantConfiguringProcessJobs;

use Exception;
use Illuminate\Support\Facades\Artisan;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ClientSideFailingProcessJobs\TenantConfiguringCancelingJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use Stancl\Tenancy\Jobs\SeedDatabase;

class TenantDatabaseSeedingCustomJob extends SeedDatabase
{

    
    public function handle()
    {
        Artisan::call('tenant-company:seed ', [
            'companyDomain' => $this->tenant->domain,
        ]);
    }

    /**
     * @throws Exception
     */
    public function failed(\Throwable $exception) : void
    {
        if(PixelTenancyManager::isItMonolithTenancyApp())
        {

            TenantApprovingCancelingJob::dispatch($this->tenant  , $exception->getMessage() , $exception->getCode());

        }elseif(PixelTenancyManager::isItTenantApp())
        {
            TenantConfiguringCancelingJob::dispatch($this->tenant->domain  , $exception->getMessage() , $exception->getCode());
        }
    }

}
