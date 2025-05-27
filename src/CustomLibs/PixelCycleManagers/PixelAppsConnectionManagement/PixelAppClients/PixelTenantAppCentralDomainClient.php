<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception; 


//to call tenant app on central part .... not on a tenant sub-domain
class PixelTenantAppCentralDomainClient extends PixelAppClient
{
    public static function getClientName() : string
    {
        return "tenant-app-central-domain";
    }

    public function getAppRootApiConfigKeyName() : string
    {
        return "tenant-app-root-api";
    }
 
    protected function getRootApiConfigValueNonSettingException() : Exception
    {
        return new Exception("No tenant app root api value is set in config file !");
    }

}