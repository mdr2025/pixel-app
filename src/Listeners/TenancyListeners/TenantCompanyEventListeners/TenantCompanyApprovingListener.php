<?php

namespace PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners;

use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApproved;
use PixelApp\Jobs\TenantCompanyJobs\TenantCompanyApprovingNotifierJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantDatabaseCreatingCustomJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantDatabaseMigratingCustomJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantDatabaseSeedingCustomJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantPassportClientsSeederJob;
use PixelApp\Jobs\TenantCompanyJobs\TenantSuperAdminSeederJob; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use PixelApp\Models\CompanyModule\TenantCompany;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Throwable;

class TenantCompanyApprovingListener implements ShouldQueue
{
    use  Queueable;
    protected TenantWithDatabase | TenantCompany $tenantCompany;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
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
            TenantDatabaseCreatingCustomJob::class,
            TenantDatabaseMigratingCustomJob::class,
            TenantDatabaseSeedingCustomJob::class,
            TenantSuperAdminSeederJob::class,
            TenantPassportClientsSeederJob::class,
           TenantCompanyApprovingNotifierJob::class
        ]);
        $job->passable = [$this->tenantCompany];
        return $job;
    }

    /**
     * @return $this
     * @throws Throwable
     */
    protected function executeJobPipeline(): self
    {
        $this->getExcutableJobPipeline()->handle();
        return $this;
    }
    protected function setTenant(TenantCompanyApproved $event): self
    {
        /** @var TenantWithDatabase $tenant  */
        $tenant = $event->tenant;
        $this->tenantCompany = $tenant;
        return $this;
    }

    /**
     * Handle the event.
     *
     * @param    $event
     * @return void
     * @throws Throwable
     */
    public function handle($event)
    {
        $this->setTenant($event)->executeJobPipeline();
    }
}
