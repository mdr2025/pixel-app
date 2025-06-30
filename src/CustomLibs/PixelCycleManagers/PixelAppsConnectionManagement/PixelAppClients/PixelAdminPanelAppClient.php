<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\GlobalRouteIdentifierFactories\ServerAppAccessTokenFecthingRouteIdentifierFactory;

class PixelAdminPanelAppClient extends PixelAppClient
{
    public static function getClientName() : string
    {
        return "admin-panel";
    }

    public function getServerAppRootApiConfigKeyName() : string
    {
        return "admin-panel-app-root-api";
    }

    protected function getRootApiConfigValueNonSettingException() : Exception
    {
        return new Exception("No Admin panel root api value is set in config file !");
    }

}