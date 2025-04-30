<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileGettingService;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\GettingCompanyProfileRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class CompanyProfileGettingClientService extends AdminPanelConnectingClientService
{    
    
    public function __construct()
    {
        BasePolicy::check( "read_company-account" );
    }
    
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new GettingCompanyProfileRouteIdentifierFactory(tenant()->domain);
    }
 
}
