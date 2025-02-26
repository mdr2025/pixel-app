<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CheckingTenantStatusRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{    
    protected function getTenantDefaultAdminEmail() : string
    {
        return tenant()->defaultAdmin->email;
    }
    protected function getData() : array
    {
        return [ "admin_email" => $this->getTenantDefaultAdminEmail() ];
    }
    protected function getUri() : string
    {
        return "api/company/check/status";
    }
    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return (new PixelAppPostRouteIdentifier($this->getUri() , $this->getData() ));
    }
}