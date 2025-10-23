<?php

namespace PixelApp\Jobs\TenantCompanyJobs\TenantConfiguringProcessJobs;
 
use PixelApp\Notifications\Company\CompanyApprovingNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany  ;

/**
 * @property  TenantCompany $tenant
 */
class TenantCompanyApprovingNotifierJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string |TenantCompany $tenantDomain;

    public function __construct(string |TenantCompany $tenantDomain )
    {
        $this->tenantDomain = $tenantDomain;
    }
    
    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->setTenant();
        $this->sendCompanyApprovingNotification();
    }

    protected function getNotification() : Notification
    {
        return new CompanyApprovingNotification($this->getTenant());
    }

    protected function getTenantDefaultAdmin() : ?CompanyDefaultAdmin
    {
        return $this->getTenant()->defaultAdmin;
    }

    protected function getTenant() : TenantCompany
    {
        if(!$this->tenantDomain instanceof TenantCompany)
        {
            $this->setTenant();
        }

        return $this->tenantDomain;
    }

    protected function setTenant() : void
    {
        if(is_string($this->tenantDomain))
        {
            $tenant = PixelTenancyManager::fetchApprovedTenantForDomain($this->tenantDomain);

            if($tenant)
            {
                $this->tenantDomain  = $tenant;
            }
        }

        if(!$this->tenantDomain instanceof TenantCompany)
        {
            throw new Exception("failed to notifiy the tenant default admin ... no approved tenant company passed to notifier class !");
        }

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

}
