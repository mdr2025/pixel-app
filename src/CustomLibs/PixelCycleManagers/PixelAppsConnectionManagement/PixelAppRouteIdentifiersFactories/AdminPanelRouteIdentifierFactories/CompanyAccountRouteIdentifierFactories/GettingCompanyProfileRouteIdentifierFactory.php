<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\Interfaces\DataSendingRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppGetRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\Traits\CompanyDomainConstructable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class GettingCompanyProfileRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{ 
    use CompanyDomainConstructable;

    protected function getData() : array
    {
        $data = request()->all();
        return array_merge($data , ["company_domain" =>  $this->companyDomain]);
    }
    protected function getUri() : string
    {
        return "api/company/profile";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return new PixelAppGetRouteIdentifier($this->getUri() , $this->getData());
    }
}