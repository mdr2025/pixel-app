<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyBranchesListServices; 
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\CompanyBranchesListingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class CompanyBranchesListClientService extends AdminPanelConnectingClientService
{    
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CompanyBranchesListingRouteIdentifierFactory(tenant()->domain);
    }
 
}
