<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthClientServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CheckingSubDomainAvailabilityRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CompanyCheckingSubDomainService extends AdminPanelConnectingClientService
{
    protected string $subDomain ;

    public function __construct(string $subDomain)
    {
        $this->subDomain = $subDomain;    
    }

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CheckingSubDomainAvailabilityRouteIdentifierFactory($this->subDomain);
    }  
}
