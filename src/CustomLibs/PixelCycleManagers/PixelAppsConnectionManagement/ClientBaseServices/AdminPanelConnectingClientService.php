<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\Services\Interfaces\PixelClientService;


abstract class AdminPanelConnectingClientService extends PixelBaseClientService implements PixelClientService
{
     
    protected function getClientName() : string
    {
        return PixelAdminPanelAppClient::getClientName();
    }   
 
}
