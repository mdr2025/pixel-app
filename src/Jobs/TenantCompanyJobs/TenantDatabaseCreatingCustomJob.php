<?php

namespace PixelApp\Jobs\TenantCompanyJobs;

use Exception; 
use Stancl\Tenancy\Jobs\CreateDatabase;
use Throwable;

class TenantDatabaseCreatingCustomJob extends CreateDatabase
{

    /**
     * @throws Exception
     */
    public function failed(Throwable $exception) : void
    {
        TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        TenantApprovingCancelingJob::dispatch($this->tenant);
        throw new Exception($exception->getMessage());
    }

}
