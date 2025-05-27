<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Models\CompanyModule\TenantCompany ; 
use Throwable;

/**
 * @property TenantCompany $tenant
 */
class TenantApprovingCancelingJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected TenantCompany $tenant;
    protected ?Throwable $failingException = null;

    public function __construct(TenantCompany $tenant , ?Throwable $exception = null)
    {
        $this->tenant = $tenant;
        $this->failingException = $exception;
    }

    /**
     * @return void
     */
    public function handle()
    {
        TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        RollbackApprovingTenantStatusChangingJob::dispatch($this->tenant);

        if($this->failingException)
        {
            throw $this->failingException;
        }
    }
}
