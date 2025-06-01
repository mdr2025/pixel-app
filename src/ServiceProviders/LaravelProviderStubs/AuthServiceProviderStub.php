<?php

namespace App\Providers;

use AuthorizationManagement\IndependentGateManagement\IndependentGateManagers\IndependentGateManager;
use AuthorizationManagement\PolicyManagement\PolicyManagers\PolicyManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager\PixelPassportManager;

class AuthServiceProvider extends ServiceProvider
{
 
    public function register()
    {
        $this->completePassportRegistrations();
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerIndependentGates();
        
        $this->completePassportBooting();
    }

    public function registerIndependentGates(): void
    {
        IndependentGateManager::Singleton()->defineAll();
    }

    public function registerPolicies()
    {
        PolicyManager::Singleton()->defineAll();
    }

    protected function completePassportRegistrations() : void
    {
        PixelPassportManager::registerPassportObjects();
    }

    private function completePassportBooting(): void
    {
        PixelPassportManager::bootPassport();
    }

}
