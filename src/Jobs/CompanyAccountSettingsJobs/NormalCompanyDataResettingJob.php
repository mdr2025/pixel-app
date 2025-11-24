<?php

namespace PixelApp\Jobs\CompanyAccountSettingsJobs;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use PixelApp\Database\Seeders\PixelSeedersManager;

class NormalCompanyDataResettingJob extends CompanyDataResettingBaseJob
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
        
        Artisan::call('db:seed', [
            '--class'   => $seederClass
        ]); 
        
        Log::info('ResetCompanyDataJob: seeder completed.');
    }

    protected function getSeederClass() : string
    {
        return match($this->resetType) {
            'full' => PixelSeedersManager::getCompanyFullResettingSeederClass(),
            'partial' => PixelSeedersManager::getCompanyPartialResetSeederClass(),
            default => PixelSeedersManager::getCompanyFullResettingSeederClass(),
        };
    }
}
