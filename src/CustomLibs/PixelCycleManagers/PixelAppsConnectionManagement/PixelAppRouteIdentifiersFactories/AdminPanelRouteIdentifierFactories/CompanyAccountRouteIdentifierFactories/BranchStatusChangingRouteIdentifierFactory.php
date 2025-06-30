<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class BranchStatusChangingRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{  
    protected int $tenantBranchId ;

    public function __construct($tenantBranchId)
    {
        $this->tenantBranchId = $tenantBranchId;
    }
    
    protected function getData() : array
    {
        return request()->all();
    }

    protected function getUriParameters() : array
    {
        return ["id" => $this->tenantBranchId];
    }

    protected function getUri() : string
    {
        return "api/company/edit-branch-status/{id}";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return new PixelAppPostRouteIdentifier($this->getUri() , $this->getData() , $this->getUriParameters());
    }
}