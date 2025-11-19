<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyDataResettingService;

use PixelApp\Jobs\CompanyAccountSettingsJobs\TenantCompanyDataResettingJob;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyDataResettingService\CompanyDataResettingBaseService;

class TenantCompanyDataResettingService extends CompanyDataResettingBaseService
{
    
    protected function dispatchDataResettingJob() : void
    {
        TenantCompanyDataResettingJob::dispatch($this->data['type']);
    }

}