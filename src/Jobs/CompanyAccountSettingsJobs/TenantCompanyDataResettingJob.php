<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Database\Seeders\TenantDatabaseSeeder;
use Illuminate\Support\Facades\Artisan;

class TenantCompanyDataResettingJob extends CompanyDataResettingBaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function seedDatabase()
    {
            Artisan::call('tenants:seed', [
                '--tenants' => [tenant()->getTenantKey()],
                '--class'   => TenantDatabaseSeeder::class
            ]); 
    }
}
