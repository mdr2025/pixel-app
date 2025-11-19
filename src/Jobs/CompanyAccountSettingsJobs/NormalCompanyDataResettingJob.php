<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Database\Seeders\CompanyResetSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class NormalCompanyDataResettingJob extends CompanyDataResettingBaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function seedDatabase()
    {
        $seederClass = CompanyResetSeeder::class;

        Log::info("ResetCompanyDataJob: running seeder: {$seederClass}");
        
        Artisan::call('db:seed', [
            '--class'   => $seederClass
        ]); 
        
        Log::info('ResetCompanyDataJob: seeder completed.');
    }
}
