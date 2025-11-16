<?php

namespace PixelApp\Listeners\TenancyListeners\TenantCompanyEventListeners;

 use Exception;
 use PixelApp\Models\CompanyModule\TenantCompany;
use Illuminate\Contracts\Queue\ShouldQueue; 

class TenantRegisteringListener implements ShouldQueue
{
 
    protected function generateCompanyIdString($event): self
    {
        /**
         * @var TenantCompany $tenant
         */
        $tenant = $event->tenant;
        $companyId = $tenant->getCompanyIdGeneratingStg()->generate();
        $tenant->fillCompanyId($companyId);

        $tenant->save();
        return $this;
    }

    /**
     * Handle the event.
     *
     * @param Object $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        $this->generateCompanyIdString($event);
    }
}
