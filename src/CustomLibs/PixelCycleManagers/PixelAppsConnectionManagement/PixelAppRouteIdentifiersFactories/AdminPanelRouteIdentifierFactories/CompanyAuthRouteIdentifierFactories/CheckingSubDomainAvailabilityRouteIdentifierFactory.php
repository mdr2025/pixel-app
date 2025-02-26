<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppGetRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\Traits\CompanyDomainConstructable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CheckingSubDomainAvailabilityRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{   
    use CompanyDomainConstructable;

    protected function getUriParameters() : array
    {
        return ["domain" => $this->companyDomain];
    }
    protected function getUri() : string
    {
        return "api/check-subdomain/{domain}";
    }
    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return (new PixelAppGetRouteIdentifier($this->getUri() , [] , $this->getUriParameters()));
    }
}