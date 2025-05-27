<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\TenantCompanySettingsControllers\TenantCompanyDataResettingController;
use PixelApp\Routes\PixelRouteManager;

class TenantCompanySettingsAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  PixelRouteManager::isItTenantApp() || PixelRouteManager::isItMonolithTenancyApp() )
        {
            $this->defineTenantAppRoutes(); 
        }
    }
    
    protected function defineCompanyResettingRoute() : void
    { 
        Route::post('reset-data', [TenantCompanyDataResettingController::class, 'resetData']);
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

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineCompanyResettingRoutes($routeRegistrar);
    }
     
}
