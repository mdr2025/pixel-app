<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception;
use PixelApp\Models\CompanyModule\TenantCompany;

class PixelTenantAppClient extends PixelAppClient
{
    protected ?TenantCompany $tenantCompany = null;

    public function getTenantCompany() : ?TenantCompany
    {
        return $this->tenantCompany;
    }

    public function setTenantCompanu(TenantCompany $tenantCompany) : self
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

    public function getAppRootApiConfigKeyName() : string
    {
        return "tenant-app-root-api";
    }

    protected function getNonSetTenantCompanyException() : Exception
    { 
        throw new Exception("Tenant Company is not set ... failed to compose the tenant client url !");
    }

    protected function getRootApiConfigValueNonSettingException() : Exception
    {
        return new Exception("No tenant app root api value is set in config file !");
    }

}