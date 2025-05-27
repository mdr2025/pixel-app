<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Models\CompanyModule\TenantCompany;

/**
 * @property TenantCompany $tenant
 */
class RollbackApprovingTenantStatusChangingJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected TenantCompany $tenant;

    public function __construct(TenantCompany $tenant)
    {
        $this->tenant = $tenant;
    }

    protected function returnTenantToDefaultRegistrationStatus() : void
    {
        $this->tenant->returnToDefaultRegistrationStatus();
        $this->tenant->save();
    }

    /**
     * @return void
     */
    public function handle()
    {
        $this->returnTenantToDefaultRegistrationStatus();
    }
}
