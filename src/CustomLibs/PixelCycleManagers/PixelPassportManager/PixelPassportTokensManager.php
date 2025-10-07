<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager;

use Laravel\Passport\Passport;
use PixelApp\Config\PixelConfigManager;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;

class PixelPassportTokensManager
{
    protected static $instance = null;

    const DEFAULT_REFRESH_TOKEN_GRACE_PERIOD = '10 days';
    const DEFAULT_REVOKED_TOKEN_GRACE_PERIOD = '10 days';

    protected function __construct(){}

    public static function Singleton() : self
    {
        if(!  static::$instance )
        {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    public function getRefreshTokenGracePeriod() : string
    {
        return PixelPassportManager::getRefreshTokenGracePeriod() ?? static::DEFAULT_REFRESH_TOKEN_GRACE_PERIOD;
    }

    public function getRevokedTokenGracePeriod() : string
    {
        return PixelPassportManager::getRevokedTokenGracePeriod() ?? static::DEFAULT_REVOKED_TOKEN_GRACE_PERIOD;
    }

    /**
     * will delete all clients ... and related personal clients , access tokens (and related refresh tokens)
     */
    public function truncateClientTable() : self
    {
        Passport::clientModel()::query()->delete();

        return $this;
    }
    
    public function truncatePersonalClientTable() : self
    {
        Passport::personalAccessClientModel()::query()->delete();

        return $this;
    }

    /**
     * will delete all access tokens (and related refresh tokens)
     */
    public function truncateAccessTokensTable() : self
    {
        Passport::tokenModel()::query()->delete();
        
        return $this;
    }

    /**
     * will delete all refresh tokens
     */
    public function truncateRefreshTokensTable() : self
    {
        Passport::refreshTokenModel()::query()->delete();
        
        return $this;
    }

    public function deleteRefreshTokensForAccessTokenIds(array $accessTokenIds) : self
    {
        Passport::refreshTokenModel()::whereIn("access_token_id" , $accessTokenIds)->delete();
        
        return $this;
    }

    protected function getThresholdDate(string $periodBeforeCurrent) : Carbon
    {
        $gracePeriod = CarbonInterval::make($periodBeforeCurrent); 
        
        return Carbon::now()->sub($gracePeriod);
    }

    
    protected function getForeignKeyDependentDeletingStatus() : bool
    {
        return PixelPassportManager::getForeignKeyDependentDeletingStatus();
    }

    //revoked tokens handling code part
    protected function getRevokedTokensExceedingGraceQuery() : Builder
    {
        $thresholdDate = $this->getThresholdDate( $this->getRevokedTokenGracePeriod() );

        return Passport::tokenModel()::where('revoked', 1)
                                     ->whereDate('updated_at', '<', $thresholdDate) ;
    }

    
    protected function purgeRevokedTokensWithRelatedRefreshTokens() : self
    {
        $tokenIds = $this->getRevokedTokensExceedingGraceQuery()->pluck("id")->toArray();
        
        $this->deleteRefreshTokensForAccessTokenIds($tokenIds);
        
        $this->purgeOnlyRevokedTokens();

        return $this;
    }

    protected function purgeOnlyRevokedTokens() : self
    {
        $this->getRevokedTokensExceedingGraceQuery()->delete();
        return $this;
    }

    public function purgeRevokedTokensExceedingGrace() : self
    {   
       if($this->getForeignKeyDependentDeletingStatus())
       {
            return $this->purgeOnlyRevokedTokens();
       }

       return $this->purgeRevokedTokensWithRelatedRefreshTokens();
    }
 
    //expired tokens handling code part
    protected function getExpiredTokensExceedingGraceQuery() : Builder
    {   
        $thresholdDate = $this->getThresholdDate( $this->getRefreshTokenGracePeriod() );
        
        return Passport::tokenModel()::whereDate('expires_at', '<', $thresholdDate);
    }

    protected function purgeExpiredTokensWithRelatedRefreshTokens() : self
    {
        $tokenIds = $this->getExpiredTokensExceedingGraceQuery()->pluck("id")->toArray();
        
        $this->deleteRefreshTokensForAccessTokenIds($tokenIds);
        
        $this->purgeOnlyExpiredTokens();

        return $this;
    }

    protected function purgeOnlyExpiredTokens() : self
    {
        $this->getExpiredTokensExceedingGraceQuery()->delete();
        return $this;
    }

    public function purgeExpiredTokensExceedingGrace() : self
    {

        if($this->getForeignKeyDependentDeletingStatus())
        {
            return $this->purgeOnlyExpiredTokens();
        }

        /**
         * If no database foreign key binding is there : any access token deleting will not delete its related refresh tokens automatically
         * so we need to delete them manually after getting the expired access token ids
         * note : in the package the foreign key is set .... but we handle the both cases in this tokens manager
         */
        return $this->purgeExpiredTokensWithRelatedRefreshTokens();
    }
}