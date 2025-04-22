<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\Traits\CompanyDomainConstructable;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class DefaultAdminDataSyncingRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{   
    use CompanyDomainConstructable;

    protected array $updatedData = [];

    public function __construct(string $companyDomain , array $updatedData)
    {
        $this->setCompanyDomain($companyDomain);
        
        $this->setUpdatedData($updatedData);
    }

    public function setUpdatedData(array $data) : self
    {
        $this->updatedData = $data;
        return $this;
    }

    public function getUpdatedData() : array
    {
        return $this->updatedData ;
    }
    
    protected function getPayload() : array
    {
        return array_merge($this->getUpdatedData()  , ["company_domain" => $this->companyDomain]);
    }

    protected function getUri() : string
    {
        return "api/company/sync-default-admin-data";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return (new PixelAppPostRouteIdentifier($this->getUri() , $this->getPayload() ));
    }
}