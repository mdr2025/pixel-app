<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Routes\PixelRouteManager;

class TenantCompanySettingsAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  PixelRouteManager::isItTenantApp())
        {
            $this->defineTenantAppRoutes(); 
        
        } elseif(PixelRouteManager::isItMonolithTenancyApp()  )
        {
            
        }
    }
    
    protected function defineCompanyResettingRoute() : void
    { 
        Route::post('reset-data', [UserCompanySettingController::class, 'resetData']);
    }
 
    protected function defineCompanyResettingRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineCompanyResettingRoute();  
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 'auth:api']  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/company');
    }
 
    
    protected function defineNormalAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachGlobalMiddlewares($routeRegistrar);

       $this->defineCompanyResettingRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineCompanyResettingRoutes($routeRegistrar);
    }
 
    
     
}
