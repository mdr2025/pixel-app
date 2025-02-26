<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CheckingCrNoValidityRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CompanyCheckingCrNoService extends AdminPanelConnectingClientService
{
    protected string $crNo ;

    public function __construct(string $crNo)
    {
        $this->crNo = $crNo;    
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CheckingCrNoValidityRouteIdentifierFactory($this->crNo);
    }  
}
