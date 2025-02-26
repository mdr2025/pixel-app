<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\DefaultAdminServices\EmailVerificationServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CompanyDefaultAdminVerifyingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

 
class DefaultAdminEmailVerificationService extends AdminPanelConnectingClientService
{ 
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CompanyDefaultAdminVerifyingRouteIdentifierFactory();
    }  
}
