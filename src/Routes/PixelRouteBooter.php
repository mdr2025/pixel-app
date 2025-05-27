<?php

namespace PixelApp\Routes;


use Illuminate\Support\Facades\Route;

class PixelRouteBooter
{

    protected static function initPixelRouteRegistrar(string $pixelRouteRegisstrarClass) : PixelRouteRegistrar
    {
        if(is_subclass_of($pixelRouteRegisstrarClass , PixelRouteRegistrar::class))
        {
            return new $pixelRouteRegisstrarClass();
        }

        //just for development enviroment on catching an error with RouteRegistrar classes
        dd($pixelRouteRegisstrarClass . " Is not a PixelRouteRegistrar typed class "); 
    }
    
    protected static function loadRouteRegistrar(mixed $routeRegistrarClass, ?callable $callbackOnRouteRegistrar = null ) : bool
    {
        if(is_string($routeRegistrarClass))
        {
            static::initPixelRouteRegistrar($routeRegistrarClass)->bootRoutes($callbackOnRouteRegistrar);
            return true;
        }

        return false;
    }

    protected static function loadRouteRegistrars(array $routeRegistrarClasses , ?callable $callbackOnRouteRegistrar = null ) : void
    {
        foreach($routeRegistrarClasses as $routeRegistrarClass)
        {
            static::loadRouteRegistrar($routeRegistrarClass , $callbackOnRouteRegistrar);
        }
    }

    public static function loadPixelAppPackageRoutes(?callable $callbackOnRouteRegistrar = null)
    { 
        foreach(PixelRouteManager::getDefinedRouteRegistrars() as $routeRegistrarClass)
        {
            if(static::loadRouteRegistrar($routeRegistrarClass , $callbackOnRouteRegistrar))
            {
                continue;
            }

            if(is_array($routeRegistrarClass))
            {
                static::loadRouteRegistrars($routeRegistrarClass , $callbackOnRouteRegistrar);
            }
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
        if(PixelRouteManager::isItTenancySupporterApp())
        {
            foreach (PixelRouteManager::getCentralDomains() as $domain) 
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
        if(PixelRouteManager::isItTenancySupporterApp())
        {
            foreach (PixelRouteManager::getCentralDomains() as $domain) 
            {
                static::loadWebFileRoutes($callbackOnRouteRegistrar , $domain);
            } 
            return;
        }  
        
        static::loadWebFileRoutes($callbackOnRouteRegistrar);
    } 
 
    public static function loadTenantRoutes()
    {
        if (PixelRouteManager::DoesItNeedTenantRoutes())
        {
            Route::prefix()->group(base_path('routes/tenant.php'));
        }
    }
    
}