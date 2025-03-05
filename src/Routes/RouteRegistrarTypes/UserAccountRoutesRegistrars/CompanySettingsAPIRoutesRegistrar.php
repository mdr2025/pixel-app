<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UserAccountControllers\UserCompanySettingController;
use PixelApp\Routes\PixelRouteManager;

class CompanySettingsAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function registerRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  PixelRouteManager::isItTenantApp() || PixelRouteManager::isItMonolithTenancyApp()  )
        {
            $this->defineTenantAppRoutes(); 
        } 
    }
    
    protected function defineUpdateAdminInfoRoute() : void
    {    
        Route::put('profile/update-admin', [UserCompanySettingController::class, 'updateAdminInfo']);
    }

    protected function defineCompanyResettingRoute() : void
    { 
        Route::post('reset-data', [UserCompanySettingController::class, 'resetData']);
    }
 
    protected function defineCompanyResettingRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineCompanyResettingRoute();  
            $this->defineUpdateAdminInfoRoute();
        });
    }
    
    protected function attachGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( [ 'api' , 'auth:api'] );
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/company');
    }

    protected function attachTenantMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $tenantMiddlewares = PixelRouteManager::getTenantMiddlewares();
        $routeRegistrar->middleware($tenantMiddlewares);
    } 

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();
       $this->attachTenantMiddlewares($routeRegistrar);
       $this->defineCompanyResettingRoutes($routeRegistrar);
    }
 
    
     
}
