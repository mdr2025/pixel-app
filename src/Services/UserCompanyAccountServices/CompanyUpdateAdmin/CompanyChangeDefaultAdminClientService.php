<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyUpdateAdmin;
 
use Illuminate\Support\Facades\DB;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CompanyChangeDefaultAdminClientService extends AdminPanelConnectingClientService
{

    protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new FecthTenantCompanyRouteIdentifierFactory();
    }
}
