<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\BranchStatusChangingServices;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAccountRouteIdentifierFactories\BranchStatusChangingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
 
 
class BranchStatusChangingClientService extends AdminPanelConnectingClientService
{    
    protected int $tenantBranchId;

    public function __construct($tenantBranchId)
    {
        BasePolicy::check("edit-branch_company-account");
        
        $this->tenantBranchId = $tenantBranchId;

    }
    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new BranchStatusChangingRouteIdentifierFactory($this->tenantBranchId);
    }
 
}
