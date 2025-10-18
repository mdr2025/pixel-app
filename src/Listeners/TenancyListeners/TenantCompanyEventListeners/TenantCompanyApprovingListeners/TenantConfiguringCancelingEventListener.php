<?php

namespace PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners\TenantCompanyApprovingListeners;

use Exception;
use PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents\TenantConfiguringCancelingEvent;
use PixelApp\Jobs\TenantCompanyJobs\TenantApprovingFailingProcessJobs\ServerSideFailingProcessJobs\TenantApprovingCancelingJob;
use PixelApp\Models\CompanyModule\TenantCompany;
use Throwable;

class TenantConfiguringCancelingEventListener
{ 

    protected TenantCompany $tenantCompany;

    /**
     * Handle the event.
     *
     * @param    $event
     * @return void
     * @throws Throwable
     */
    public function handle(TenantConfiguringCancelingEvent $event)
    {
        $tenant = $event->getTenant();
        
        $approvingCancelingFailingException = $this->customizeFailingException($event);

        TenantApprovingCancelingJob::dispatch(
                                                $tenant ,
                                                $approvingCancelingFailingException->getMessage() ,
                                                $approvingCancelingFailingException->getCode()
                                            );
    }

    protected function customizeFailingException(TenantConfiguringCancelingEvent $event) : Throwable
    {
        $failingException = $event->getFailingException();
        
        if(!$failingException)
        {
            $failingException = new Exception("Tenant resources configuring process has been canceled !");
        }

        return $failingException;
    }
}
