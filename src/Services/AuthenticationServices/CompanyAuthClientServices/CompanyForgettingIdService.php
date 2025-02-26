<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CompanyForgettingIdRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;


class CompanyForgettingIdService extends AdminPanelConnectingClientService
{

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CompanyForgettingIdRouteIdentifierFactory();
    }  
}
