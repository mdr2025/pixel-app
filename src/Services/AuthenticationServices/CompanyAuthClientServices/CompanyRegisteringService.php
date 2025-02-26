<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CompanyRegisteringRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

 class CompanyRegisteringService extends AdminPanelConnectingClientService
{
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CompanyRegisteringRouteIdentifierFactory();
    }
 }
