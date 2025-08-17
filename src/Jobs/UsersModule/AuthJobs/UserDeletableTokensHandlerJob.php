<?php

namespace PixelApp\Jobs\UsersModule\AuthJobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager\PixelPassportManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager\PixelPassportTokensManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\CompanyModule\TenantCompany;

class UserDeletableTokensHandlerJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
    protected function purgeExpiredTokensExceedingGrace() : void
    {
        PixelPassportTokensManager::Singleton()->purgeExpiredTokensExceedingGrace();
    }
    
    protected function purgeRevokedTokensExceedingGrace() : void
    {
        PixelPassportTokensManager::Singleton()->purgeRevokedTokensExceedingGrace();
    }

    protected function doesHaveTokensInBothSide()
    {
        return PixelPassportManager::doesHaveTokensInBothSide();
    }

    protected function doesHaveOnlyTenantTokens()
    {
        return PixelPassportManager::doesHaveOnlyTenantTokens();
    }

    protected function doesHaveOnlyCentralTokens() : bool
    {
        return PixelPassportManager::doesHaveOnlyCentralTokens();    
    }

    protected function fetchTenantsByAdminPanel() : Collection
    {
        return PixelTenancyManager::fetchTenantsByAdminPanel();
    }

    protected function fetchTenantsFromCentralSide() : Collection
    {
        return PixelTenancyManager::fetchTenantsFromCentralSide();
    }
 
    protected function purgeTenantsTokensExceedingGrace(Collection $tenants) : void
    {
        foreach($tenants as $tenant)
        {
            if($tenant instanceof TenantCompany)
            {
                $tenant->run(function()
                {
                    $this->purgExpiredTokensForCurrentContext();
                    $this->purgRevokedTokensForCurrentContext();
                });
            }
        }
    }

    protected function purgExpiredTokensForCurrentContext() : void
    {
        $this->purgeExpiredTokensExceedingGrace();
    }

    protected function purgRevokedTokensForCurrentContext() : void
    {
        $this->purgeRevokedTokensExceedingGrace();
    }

    protected function purgeCentralTokensExceedingGrace() : void
    {
        $this->purgExpiredTokensForCurrentContext();
        $this->purgRevokedTokensForCurrentContext();
    }

    protected function purgeBothSideTokensExceedingGrace() : void
    {
        $this->purgeCentralTokensExceedingGrace();

        $tenants = $this->fetchTenantsFromCentralSide();
        $this->purgeTenantsTokensExceedingGrace($tenants);   
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {

        if($this->doesHaveOnlyCentralTokens())
        {
            $this->purgeCentralTokensExceedingGrace();
        
        }elseif($this->doesHaveOnlyTenantTokens())
        {
            $tenants = $this->fetchTenantsByAdminPanel();
            $this->purgeTenantsTokensExceedingGrace($tenants);
        
        }elseif($this->doesHaveTokensInBothSide())
        {
            $this->purgeBothSideTokensExceedingGrace();
        }
    }
}
