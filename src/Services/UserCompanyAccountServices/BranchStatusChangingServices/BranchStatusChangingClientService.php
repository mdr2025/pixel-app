<?php

namespace PixelApp\Services\UserCompanyAccountServices\BranchStatusChangingServices; 
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\BranchStatusChangingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class BranchStatusChangingClientService extends AdminPanelConnectingClientService
{    
    protected int $tenantBranchId;

    public function __construct($tenantBranchId)
    {
        $this->tenantBranchId = $tenantBranchId;
    }
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new BranchStatusChangingRouteIdentifierFactory($this->tenantBranchId);
    }
 
}
