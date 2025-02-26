<?php

namespace PixelApp\Jobs\TenantCompanyJobs;

use Exception;
use Stancl\Tenancy\Jobs\MigrateDatabase;

class TenantDatabaseMigratingCustomJob extends MigrateDatabase
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
