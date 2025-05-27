<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices;
 
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelTenantAppCentralDomainClient;
use PixelApp\Services\Interfaces\PixelClientService;


abstract class TenantAppCentralConnectingClientService extends PixelBaseClientService implements PixelClientService
{
     
    protected function getClientName() : string
    {
        return PixelTenantAppCentralDomainClient::getClientName();
    }

}
