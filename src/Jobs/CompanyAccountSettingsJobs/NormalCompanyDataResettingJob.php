<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Database\Seeders\CompanyResetSeeder;
use Illuminate\Support\Facades\Artisan;

class NormalCompanyDataResettingJob extends CompanyDataResettingBaseJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function seedDatabase()
    {
            Artisan::call('db:seed', [
                '--class'   => CompanyResetSeeder::class
            ]); 
    }
}
