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
            
        } else // for admin panel  + normal app
        {
            $this->defineNormalAppRoutes();
        }
    }
    
    protected function defineUpdateAdminInfoRoute() : void
    {    
        Route::put('profile/update-admin', [UserCompanySettingController::class, 'changeDefaultAdmin']);
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
            $this->defineUpdateAdminInfoRoute();
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
