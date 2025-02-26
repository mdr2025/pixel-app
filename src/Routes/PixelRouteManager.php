<?php

namespace PixelApp\Routes;
 
use Illuminate\Support\Facades\Route; 
use PixelApp\Config\PixelConfigManager; 
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager; 

class PixelRouteManager
{

    protected static function getPixelAppPackageRouteRegistrars() : array
    {
        return array_filter(
                        PixelConfigManager::getPixelAppPackageRouteRegistrars() ,
                        function($class)
                        {
                            return !is_null($class);
                        }
                    );
    }

    protected function initPixelRouteRegistrar(string $pixelRouteRegisstrarClass) : PixelRouteRegistrar
    {
        if(is_subclass_of($pixelRouteRegisstrarClass , PixelRouteRegistrar::class))
        {
            return new $pixelRouteRegisstrarClass();
        }

        //just for development enviroment on catching an error with RouteRegistrar classes
        dd($pixelRouteRegisstrarClass . "Is not a PixelRouteRegistrar typed class "); 
    }

    public static function loadPixelAppPackageRoutes(?callable $callbackOnRouteRegistrar = null)
    { 
        foreach(static::getPixelAppPackageRouteRegistrars() as $routeRegistrarClass)
        {
            static::initPixelRouteRegistrar($routeRegistrarClass)->registerRoutes($callbackOnRouteRegistrar);
        }
    }
  
    protected static function loadApiFileRoutes( ?callable $callbackOnRouteRegistrar = null , ?string $domain = null ) : void
    {
        $routeRegistrar = Route::prefix('api')->middleware('api') ;

        if($domain)
        {
            $routeRegistrar->domain($domain);
        }
        
        if(is_callable($callbackOnRouteRegistrar))
        {
            call_user_func($callbackOnRouteRegistrar , $routeRegistrar);
        }
         
        $routeRegistrar->group(base_path('routes/api.php'));
    }

    public static function loadAPIRoutes(?callable $callbackOnRouteRegistrar = null)
    {
        if(static::isItTenancySupportyerApp())
        {
            foreach (static::getCentralDomains() as $domain) 
            {
                static::loadApiFileRoutes($callbackOnRouteRegistrar , $domain);
            } 
            return;
        }  

        static::loadApiFileRoutes($callbackOnRouteRegistrar);
    }

    protected static function loadWebFileRoutes( ?callable $callbackOnRouteRegistrar = null , ?string $domain = null ) : void
    {
        $routeRegistrar = Route::middleware('web') ;

        if($domain)
        {
            $routeRegistrar->domain($domain);
        }
        
        if(is_callable($callbackOnRouteRegistrar))
        {
            call_user_func($callbackOnRouteRegistrar , $routeRegistrar);
        }
         
        $routeRegistrar->group(base_path('routes/web.php'));
    }

    public static function loadWebRoutes(?callable $callbackOnRouteRegistrar = null)
    {
        if(static::isItTenancySupportyerApp())
        {
            foreach (static::getCentralDomains() as $domain) 
            {
                static::loadWebFileRoutes($callbackOnRouteRegistrar , $domain);
            } 
            return;
        }  
        
        static::loadWebFileRoutes($callbackOnRouteRegistrar);
    } 
 
    public function loadTenantRoutes()
    {
        if (static::isItTenancySupportyerApp())
        {
            Route::group(base_path('routes/tenant.php'));
        }
    }

    public static function isItTenancySupportyerApp() : bool
    {
        return PixelTenancyManager::isItTenancySupportyerApp();
    }

    public static function isItMonolithTenancyApp() : bool
    {
        return PixelTenancyManager::isItMonolithTenancyApp();
    }
 
    public static function isItAdminPanelApp() : bool
    {
        return PixelTenancyManager::isItAdminPanelApp();
    }

    public static function isItTenantApp() : bool
    {
        return PixelTenancyManager::isItTenantApp();
    }

    public static function getCentralDomains(): array
    {
        return PixelTenancyManager::getCentralDomains();
    }
    public static function getTenantMiddlewares() : array
    {
        return PixelTenancyManager::getTenantDefaultMiddlewares();
    }
  
    protected static function initPixelAppRouteStubsManager() : PixelRouteStubsManager
    {
        return PixelRouteStubsManager::Singleton();
    }
 
    public static function installPackageRoutesFiles() : void
    {
        static::initPixelAppRouteStubsManager()->replacePixelAppRouteStubs();
    }
}
// separated admin panel = app without tenancy + company auth server  => needs routes without central domains because it is on a single domain
// separated tenant app => needs routes with central with central routes and company auth client != monolith
// tenant app with admin panel => needs routes with central routes and company auth server = monolith
// app without tenancy => deosn\'t need central routes or company auth