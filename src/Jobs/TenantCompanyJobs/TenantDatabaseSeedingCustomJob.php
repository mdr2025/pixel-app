<?php

namespace PixelApp\Jobs\TenantCompanyJobs;

use Exception;
use Stancl\Tenancy\Jobs\SeedDatabase;

class TenantDatabaseSeedingCustomJob extends SeedDatabase
{
    /**
     * @throws Exception
     */
    public function failed(\Throwable $exception) : void
    {
        TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        TenantApprovingCancelingJob::dispatch($this->tenant);
        throw new Exception($exception->getMessage());
    }
}
