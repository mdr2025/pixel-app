<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception;

class PixelAdminPanelAppClient extends PixelAppClient
{
    public static function getClientName() : string
    {
        return "admin-panel";
    }

    public function getAppRootApiConfigKeyName() : string
    {
        return "admin-panel-app-root-api";
    }

    protected function getRootApiConfigValueNonSettingException() : Exception
    {
        return new Exception("No Admin panel root api value is set in config file !");
    }

}