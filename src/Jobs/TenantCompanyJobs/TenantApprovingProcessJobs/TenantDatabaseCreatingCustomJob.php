<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingProcessJobs;

use Exception;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Throwable;

class TenantDatabaseCreatingCustomJob extends CreateDatabase
{

    /**
     * @throws Exception
     */
    public function failed(Throwable $exception) : void
    {
        TenantApprovingCancelingJob::dispatch($this->tenant , $exception->getMessage() , $exception->getCode());
    }

}
