<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantConfiguringProcessJobs;

use Exception;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ClientSideFailingProcessJobs\TenantConfiguringCancelingJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use Stancl\Tenancy\Jobs\SeedDatabase;

class TenantDatabaseSeedingCustomJob extends SeedDatabase
{
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
            TenantConfiguringCancelingJob::dispatch($this->tenant  , $exception->getMessage() , $exception->getCode());
        }


        // TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        // TenantApprovingCancelingJob::dispatch($this->tenant);
        // throw new Exception($exception->getMessage());
    }
}
