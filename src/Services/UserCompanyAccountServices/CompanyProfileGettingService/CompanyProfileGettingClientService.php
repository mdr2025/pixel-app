<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyProfileGettingService; 
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\GettingCompanyProfileRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class CompanyProfileGettingClientService extends AdminPanelConnectingClientService
{    
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new GettingCompanyProfileRouteIdentifierFactory(tenant()->domain);
    }
 
}
