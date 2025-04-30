<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileUpdatingService;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\UpdateCompanyProfileRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class CompanyProfileUpdatingClientService extends AdminPanelConnectingClientService
{    

    
    public function __construct()
    {
        BasePolicy::check( "edit_company-account" );
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new UpdateCompanyProfileRouteIdentifierFactory(tenant()->domain);
    }
 
}
