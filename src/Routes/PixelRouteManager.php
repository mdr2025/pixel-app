<?php

namespace PixelApp\Routes;
 
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Config\PixelConfigManager; 
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager; 

class PixelRouteManager
{

    public static function getDefinedRouteRegistrars() : array
    {
        return array_filter(
                        PixelConfigManager::getDefinedRouteRegistrars() ,
                        function($class)
                        {
                            return !is_null($class);
                        }
                    );
    }

    public static function getPackageAllRouteRegistrars() : array
    {
        /**
         * @todo to fill later
         */
        return [];
    }

    public static function getCentralDomains(): array
    {
        return PixelTenancyManager::getCentralDomains();
    }

    public static function getTenancyMiddlewares() : array
    {
        return PixelTenancyManager::getTenantDefaultMiddlewares();
    }

    public static function loadAPIRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        PixelRouteBootingManager::loadAPIRoutes($callbackOnRouteRegistrar);
    }
    
    public static function loadWebRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        PixelRouteBootingManager::loadWebRoutes($callbackOnRouteRegistrar);
    }
    
    public static function loadPixelAppPackageRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        PixelRouteBootingManager::loadPixelAppPackageRoutes($callbackOnRouteRegistrar);
    }
    
    public static function loadTenantRoutes() : void
    {
        PixelRouteBootingManager::loadTenantRoutes();
    }

    protected static function initPixelRoutesInstallingManager() : PixelRoutesInstallingManager
    {
        return PixelRoutesInstallingManager::Singlton();
    }

    public static function installPackageRoutes() : void
    {
        static::initPixelRoutesInstallingManager()->installPackageRoutes();
    }
}
// separated admin panel = app without tenancy + company auth server  => needs routes without central domains because it is on a single domain
// separated tenant app => needs routes with central with central routes and company auth client != monolith
// tenant app with admin panel => needs routes with central routes and company auth server = monolith
// app without tenancy => deosn\'t need central routes or company auth