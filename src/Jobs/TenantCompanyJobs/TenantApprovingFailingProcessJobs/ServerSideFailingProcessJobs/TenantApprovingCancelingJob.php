<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs;

use Exception;
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
    protected  ?string $failingExceptionMessage = null ;
    protected ?int $failingExceptionCode = null ;

    public function __construct(
                                TenantCompany $tenant ,
                                ?string $failingExceptionMessage = null ,
                                ?int $failingExceptionCode = null 
                               )
    {
        $this->tenant = $tenant;
        $this->failingExceptionMessage = $failingExceptionMessage;
        $this->failingExceptionCode = $failingExceptionCode;
    }

    /**
     * @return void
     */
    public function handle()
    {
        TenantDeletingDatabaseCustomJob::dispatch($this->tenant);
        RollbackApprovingTenantStatusChangingJob::dispatch($this->tenant);

        if($this->failingExceptionMessage)
        {
            throw new Exception($this->failingExceptionMessage , $this->failingExceptionCode ?? 500);
        }
    }
}
