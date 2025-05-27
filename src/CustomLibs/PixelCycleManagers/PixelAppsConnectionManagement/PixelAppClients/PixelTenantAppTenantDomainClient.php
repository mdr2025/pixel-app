<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception;
use PixelApp\Models\CompanyModule\TenantCompany;

//to call tenant app on tenant part (on a tenant sub-domain)
class PixelTenantAppTenantDomainClient extends PixelTenantAppCentralDomainClient
{
    protected ?TenantCompany $tenantCompany = null;

    public static function getClientName() : string
    {
        return "tenant-app-tenant-domain";
    }

    public function getTenantCompany() : ?TenantCompany
    {
        return $this->tenantCompany;
    }

    public function setTenantCompany(TenantCompany $tenantCompany) : self
    {
        $this->tenantCompany = $tenantCompany;
        return $this;
    }

    protected function getTenantCompanyDomain() : string
    {
        return $this->getTenantCompany()?->domain ?? throw $this->getNonSetTenantCompanyException();
    }

    protected function getAppRootApi() : string
    {
        $domain = $this->getTenantCompanyDomain();
        return $domain . "." . parent::getAppRootApi();
    }
 
    protected function getNonSetTenantCompanyException() : Exception
    { 
        throw new Exception("Tenant Company is not set ... failed to compose the tenant client url !");
    }
 

}