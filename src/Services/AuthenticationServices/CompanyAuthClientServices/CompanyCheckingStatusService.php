<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CheckingTenantStatusRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CompanyCheckingStatusService extends AdminPanelConnectingClientService
{
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CheckingTenantStatusRouteIdentifierFactory();
    }  
}
