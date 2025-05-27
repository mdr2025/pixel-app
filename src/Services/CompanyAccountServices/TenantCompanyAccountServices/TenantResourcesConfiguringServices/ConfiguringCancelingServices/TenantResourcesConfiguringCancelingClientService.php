<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringCancelingServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\TenantAppCentralDomainRouteIdentifierFactories\TenantResourcesConfiguringCancelingRouteIdentifierFactory;
use PixelApp\Models\CompanyModule\TenantCompany;
use Throwable;

/**
 * client : tenant app - central domain
 * server : admin panel
 */
class TenantResourcesConfiguringCancelingClientService extends AdminPanelConnectingClientService
{   
    protected TenantCompany $tenant;
    protected ?Throwable $failingException = null;

    public function __construct(TenantCompany $tenant , ?Throwable $failingException = null)
    {
        $this->tenant = $tenant;    
        $this->failingException = $failingException;
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new TenantResourcesConfiguringCancelingRouteIdentifierFactory($this->tenant->domain , $this->failingException);
    }
 
}
