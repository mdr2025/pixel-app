<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Models\CompanyModule\TenantCompany ;
use Stancl\JobPipeline\JobPipeline;
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
        $this->getExcutableJobPipeline()->handle();

        if($this->failingExceptionMessage)
        {
            throw new Exception($this->failingExceptionMessage , $this->failingExceptionCode ?? 500);
        }
    }

    protected function getExcutableJobPipeline(): JobPipeline
    {
        /**
         * The algorithm we use:
         *
         * JobPipeline execute all jobs by calling handle method by Container's call method ... so each of them will not be dispatched as a single job
         * so all jobs will be handled one by one ... and if an exception is thrown the execution will stop and ( failed job's method will be called or the thrown exception will be thrown )
         * even we asked the JobPipeline to be queued it will be queued itself ... not the jobs it contains
         * so while we call this listener as a queued listener ... no need to queue the JobPipeline again
         * and no need to register the JobPipeline in TenancyServiceProvider because we will not use it as a listener .. so we need to set passable array manually
         */
        $job =  JobPipeline::make([
                                    TenantDeletingDatabaseCustomJob::class,
                                    RollbackApprovingTenantStatusChangingJob::class
                                  ]);
        $job->passable = [$this->tenant];
        return $job;
    }
}
