<?php

namespace PixelApp\Jobs\TenantCompanyJobs;
 
use PixelApp\Notifications\Company\CompanyApprovingNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany  ;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

/**
 * @property TenantWithDatabase | TenantCompany $tenant
 */
class TenantCompanyApprovingNotifierJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected TenantWithDatabase | TenantCompany $tenant;

    public function __construct(TenantWithDatabase $tenant)
    {
        $this->tenant = $tenant;
    }

    protected function getNotification() : Notification
    {
        return new CompanyApprovingNotification($this->tenant);
    }
    protected function getTenantDefaultAdmin() : ?CompanyDefaultAdmin
    {
        return $this->tenant->defaultAdmin;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function sendCompanyApprovingNotification()  :void
    {
        if(!$defaultAdmin = $this->getTenantDefaultAdmin())
        {
            throw new Exception("Failed to send company approving notification ... No default admin is found ! ");
        }
        $defaultAdmin->notify( $this->getNotification() );
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->sendCompanyApprovingNotification();
    }
}
