<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\NormalCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\NormalCompanySettingsControllers\NormalCompanyDataResettingController;
use PixelApp\Routes\PixelRouteBootingManager;

class NormalCompanySettingsAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  
            PixelRouteBootingManager::isBootingForAdminPanelApp()
            ||
            PixelRouteBootingManager::isBootingForNormalApp() )
        {
            $this->defineNormalAppRoutes(); 
        }
    }

   public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
   {
       $arrayToAppend["pixel-app-package-route-registrars"]["normal-company-settings"] = static::class;
   }

    protected function defineCompanyResettingRoute() : void
    { 
        Route::post('system-reset', [NormalCompanyDataResettingController::class, 'resetData']);
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
        return Route::prefix('api');
    }

    protected function defineNormalAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachGlobalMiddlewares($routeRegistrar);

       $this->defineCompanyResettingRoutes($routeRegistrar);
    }
     
}
