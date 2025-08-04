<?php 

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers;

use PixelApp\Config\PixelConfigManager;

class PixelAppBootingManager
{
  public static function isBootingForTenancySupporterApp() : bool
    {
        return !PixelConfigManager::isItNormalApp();
    }

    public static function isBootingForNormalApp() : bool
    {
        return PixelConfigManager::isItNormalApp();
    }
    
    public static function isBootingForMonolithTenancyApp() : bool
    {
        return PixelConfigManager::isItMonolithTenancyApp();
    }
 
    public static function isBootingForAdminPanelApp() : bool
    {
        return PixelConfigManager::isItAdminPanelApp();
    }

    public static function isBootingForTenantApp() : bool
    {
        return PixelConfigManager::isItTenantApp();
    }

}