<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\Traits\CompanyDomainConstructable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class DefaultAdminDataSyncingRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{   
    use CompanyDomainConstructable;

    protected function getData() : array
    {
        return array_merge(request()->all()  , ["company_domain" => $this->companyDomain]);
    }

    protected function getUri() : string
    {
        return "api/company/sync-default-admin-data";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return (new PixelAppPostRouteIdentifier($this->getUri() , $this->getData() ));
    }
}