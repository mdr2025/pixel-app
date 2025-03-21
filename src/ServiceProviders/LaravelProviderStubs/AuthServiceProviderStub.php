<?php

namespace App\Providers;

use App\Models\WorkSector\UsersModule\RefreshToken;
use AuthorizationManagement\IndependentGateManagement\IndependentGateManagers\IndependentGateManager;
use AuthorizationManagement\PolicyManagement\PolicyManagers\PolicyManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;


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
        --passport-migrations-ignoring--  
    }


    private function limitPassportRefreshTokenExpirationDate(): self
    {
        $days = config("passport.refresh_token_expiration_days_count");
        $date = now()->addDays($days);
        Passport::refreshTokensExpireIn($date);
        return $this;
    }
    private function limitPassportPersonalAccessTokenExpirationDate(): self
    {
        $days = config("passport.personal_access_token_expiration_days_count");
        $date = now()->addDays($days);
        Passport::personalAccessTokensExpireIn($date);
        return $this;
    }
    private function limitPassportAccessTokenExpirationDate(): self
    {
        $days = config("passport.access_token_expiration_days_count");
        $date = now()->addDays($days);
        Passport::tokensExpireIn($date);
        return $this;
    }
    private function completePassportBooting(): void
    {
        Passport::useRefreshTokenModel(RefreshToken::class);

        $this->limitPassportAccessTokenExpirationDate()
            ->limitPassportPersonalAccessTokenExpirationDate()
            ->limitPassportRefreshTokenExpirationDate();

        Passport::hashClientSecrets();

        Passport::loadKeysFrom(storage_path());
    }

}
