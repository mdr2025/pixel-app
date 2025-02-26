<?php

namespace PixelApp\Jobs\TenantCompanyJobs;

use PixelApp\Models\WorkSector\CompanyModule\TenantCompany;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Models\CompanyModule\TenantCompany as CompanyModuleTenantCompany;
use Stancl\Tenancy\Contracts\TenantWithDatabase; 

/**
 * @property TenantCompany $tenant
 */
class TenantApprovingCancelingJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected CompanyModuleTenantCompany | TenantWithDatabase $tenant;

    public function __construct(TenantWithDatabase $tenant)
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
