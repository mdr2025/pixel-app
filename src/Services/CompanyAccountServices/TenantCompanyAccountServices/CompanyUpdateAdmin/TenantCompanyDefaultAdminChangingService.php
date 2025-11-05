<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyUpdateAdmin;
 
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager; 
use PixelApp\Models\CompanyModule\TenantCompany; 
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyUpdateAdmin\CompanyDefaultAdminChangingBaseService;
 
/**
 * all user fetching is done in tenant db ... so don't forget to impplement tenancy middlewares on this route
 */
class TenantCompanyDefaultAdminChangingService  extends CompanyDefaultAdminChangingBaseService
{ 
    protected function fetchCurrentDefaultUserAdmin() : ?PixelUser
    {
        $userModelClalss = $this->getUserModelClass();
        return $userModelClalss::where('email', $this->getTenantCurrentDefaultAdminEmail())->first();
    }
   
    protected function getTenant() : TenantCompany
    {
        return tenant();
    }
   
    protected function getTenantCurrentDefaultAdminEmail(): string
    {
        return $this->getTenant()->defaultAdmin->email;
    }
     
    protected function syncDataWithAdminPanel(): void
    { 
        PixelTenancyManager::handleTenancySyncingData($this->newAdminUser); 
    }

    protected function afterDBTransactionCommited() : void
    {
        $this->syncDataWithAdminPanel();
    }
}
