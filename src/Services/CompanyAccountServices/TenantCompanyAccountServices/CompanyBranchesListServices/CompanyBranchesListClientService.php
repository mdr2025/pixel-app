<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyBranchesListServices;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\CompanyBranchesListingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class CompanyBranchesListClientService extends AdminPanelConnectingClientService
{    
    public function __construct()
    {
        BasePolicy::check( "read-branch_company-account" );
    }
    
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CompanyBranchesListingRouteIdentifierFactory(tenant()->domain);
    }
 
}
