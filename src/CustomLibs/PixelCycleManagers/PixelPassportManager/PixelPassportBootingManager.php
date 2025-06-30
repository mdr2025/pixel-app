<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager;

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Passport;
use PixelApp\Models\UsersModule\RefreshToken;

class PixelPassportBootingManager
{
    protected static $instance = null;

    protected function __construct(){}

    public static function Singleton() : self
    {
        if(!  static::$instance )
        {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    public function bootPassport() : void
    {
        
        $this->useRefreshTokenModel();

        $this->limitPassportAccessTokenExpirationDate()
             ->limitPassportPersonalAccessTokenExpirationDate()
             ->limitPassportRefreshTokenExpirationDate();

        $this->hashClientSecrets();

        $this->setPassportKeysPath();
        
        $this->registerClientCredentialsTokenRoute();
    }

    protected function setPassportKeysPath() : void
    {
        Passport::loadKeysFrom(storage_path());
    }
    
    /**
     * if you change this you must re create the personal client and save its id , secret in config
     * which is can be done by our custom command
     */
    protected function hashClientSecrets() : void
    {
        Passport::hashClientSecrets();
    }

    protected function useRefreshTokenModel() : void
    {
        Passport::useRefreshTokenModel(RefreshToken::class);
    }

    protected function limitPassportRefreshTokenExpirationDate(): self
    {
        $days = config("passport.refresh_token_expiration_days_count");
        $date = now()->addDays($days);
        Passport::refreshTokensExpireIn($date);
        return $this;
    }

    protected function limitPassportPersonalAccessTokenExpirationDate(): self
    {
        $days = config("passport.personal_access_token_expiration_days_count");
        $date = now()->addDays($days);
        Passport::personalAccessTokensExpireIn($date);
        return $this;
    }

    protected function limitPassportAccessTokenExpirationDate(): self
    {
        $days = config("passport.access_token_expiration_days_count");
        $date = now()->addDays($days);
        Passport::tokensExpireIn($date);
        return $this;
    }

    
    protected function doesItSupportMachineClientCredentialsGrant() : bool
    {
        return PixelPassportManager::doesItSupportMachineClientCredentialsGrant();
    }

    protected function registerClientCredentialsTokenRoute() : void
    {
        if($this->doesItSupportMachineClientCredentialsGrant())
        {
            Route::post('oauth/token', [AccessTokenController::class , 'issueToken'])
                 ->middleware('throttle:100,1')
                 ->name('passport.token');
        }
    }
}