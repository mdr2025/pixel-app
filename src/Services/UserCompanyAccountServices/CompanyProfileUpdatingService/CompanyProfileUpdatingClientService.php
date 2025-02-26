<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyProfileUpdatingService; 
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\UpdateCompanyProfileRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class CompanyProfileUpdatingClientService extends AdminPanelConnectingClientService
{    
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new UpdateCompanyProfileRouteIdentifierFactory(tenant()->domain);
    }
 
}
