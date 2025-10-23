<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\TenantResourcesConfiguringServices\ConfiguringCancelingServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\TenantResourcesConfiguringRouteIdentifierFactories\TenantResourcesConfiguringCancelingRouteIdentifierFactory;
use PixelApp\Models\CompanyModule\TenantCompany;
use Throwable;

/**
 * client : tenant app - central domain
 * server : admin panel
 */
class TenantResourcesConfiguringCancelingClientService extends AdminPanelConnectingClientService
{   
    protected string $companyDomain;
    protected ?Throwable $failingException = null;

    public function __construct(string $companyDomain , ?Throwable $failingException = null)
    {
        $this->companyDomain = $companyDomain;    
        $this->failingException = $failingException;
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new TenantResourcesConfiguringCancelingRouteIdentifierFactory($this->companyDomain , $this->failingException);
    }
 
}
