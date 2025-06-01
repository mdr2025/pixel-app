<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppGetRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class FecthApprovedTenantCompanyIDSRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{ 
    protected function getUri() : string
    {
        return "api/company/fecth-active-company-ids";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return new PixelAppGetRouteIdentifier($this->getUri());
    }
}