<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use PixelApp\Database\Seeders\PixelSeedersManager;

class TenantCompanyDataResettingJob extends CompanyDataResettingBaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function seedDatabase()
    {
        $seederClass = $this->getSeederClass();

        Log::info("ResetCompanyDataJob: running seeder: {$seederClass}");
          
        Artisan::call('tenant-company:seed', [
            'companyDomain' => tenant()->domain,
            '--class'   => $seederClass
        ]); 

        Log::info('ResetCompanyDataJob: seeder completed.');
    }

    protected function getSeederClass() : string
    {
        return match($this->resetType) {
            'full' => PixelSeedersManager::getTenantCompanyFullResettingSeederClass(),
            'partial' => PixelSeedersManager::getTenantCompanyPartialResettingSeederClass(),
            default => PixelSeedersManager::getTenantCompanyFullResettingSeederClass(),
        };
    }
}
