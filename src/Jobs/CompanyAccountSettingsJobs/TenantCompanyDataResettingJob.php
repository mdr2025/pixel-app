<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Database\Seeders\TenantDatabaseSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TenantCompanyDataResettingJob extends CompanyDataResettingBaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function seedDatabase()
    {
        $seederClass = TenantDatabaseSeeder::class;

        Log::info("ResetCompanyDataJob: running seeder: {$seederClass}");
          
        Artisan::call('tenant-company:seed', [
            'companyDomain' => tenant()->domain,
            '--class'   => TenantDatabaseSeeder::class
        ]); 

        Log::info('ResetCompanyDataJob: seeder completed.');
    }

    protected function getSeedingClass() : string
    {
        
    }
}
