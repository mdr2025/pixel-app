<?php

namespace PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyDataResettingService;

use PixelApp\Jobs\CompanyAccountSettingsJobs\NormalCompanyDataResettingJob;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyDataResettingService\CompanyDataResettingBaseService;

class NormalCompanyDataResettingService extends CompanyDataResettingBaseService
{
    
    protected function dispatchDataResettingJob() : void
    {
        NormalCompanyDataResettingJob::dispatch();
    }

}