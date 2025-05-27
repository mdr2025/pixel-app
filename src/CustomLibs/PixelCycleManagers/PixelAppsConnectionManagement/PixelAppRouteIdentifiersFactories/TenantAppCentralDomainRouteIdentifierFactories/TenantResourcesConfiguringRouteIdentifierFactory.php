<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\TenantAppCentralDomainRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\Traits\CompanyDomainConstructable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class TenantResourcesConfiguringRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{ 
    use CompanyDomainConstructable;

    protected function getData() : array
    {
        $data = request()->all();
        return array_merge($data , ["company_domain" =>  $this->companyDomain]);
    }

    protected function getUri() : string
    {
        return "api/company/configure-resources";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return new PixelAppPostRouteIdentifier($this->getUri() , $this->getData());
    }
}