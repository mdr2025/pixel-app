<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CompanyDefaultAdminVerifyingRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{   
    protected function getData() : array
    {
        return request()->all();
    }
    protected function getUri() : string
    {
        return "api/company/verify-email";
    }
    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return (new PixelAppPostRouteIdentifier($this->getUri() , $this->getData()));
    }
}