<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\TenantAppCentralConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\TenantAppCentralDomainRouteIdentifierFactories\TenantResourcesConfiguringRouteIdentifierFactory;
use PixelApp\Models\CompanyModule\TenantCompany;

/**
 * client : admin panel 
 * server : tenant app central domain
 */
class TenantResourcesConfiguringClientService extends TenantAppCentralConnectingClientService
{   
    protected TenantCompany $tenant;

    public function __construct(TenantCompany $tenant)
    {
        $this->tenant = $tenant;    
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new TenantResourcesConfiguringRouteIdentifierFactory($this->tenant->domain);
    }
 
}
