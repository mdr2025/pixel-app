<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\NormalCompanyAccountControllers\NormalCompanyAccountController;
use PixelApp\Routes\PixelRouteBootingManager;

class NormalCompanyProfileAPIRoutesRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( 
            PixelRouteBootingManager::isBootingForMonolithTenancyApp()  
            ||
            PixelRouteBootingManager::isBootingForAdminPanelApp()
            ||
            PixelRouteBootingManager::isBootingForNormalApp()  
          )
        {
            $this->defineNormalAppRoutes(); 
        }
    }

   public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
   {
       $arrayToAppend["normal-company-profile"] = static::class;
   }

    protected function defineUpdateCompanyProfileRoute() : void
    {
        Route::post('company/profile/update', [NormalCompanyAccountController::class, 'updateCompanyProfile']);
    }
 
    protected function defineCompanyProfileRoute() : void
    { 
        Route::get('company', [NormalCompanyAccountController::class, 'companyProfile']);
    }

    protected function defineUpdateAdminInfoRoute() : void
    {    
        Route::put('admin-info', [NormalCompanyAccountController::class, 'changeDefaultAdmin']);
    }

    protected function defineCompanyAccountRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineCompanyProfileRoute(); 
            $this->defineUpdateCompanyProfileRoute(); 
            $this->defineUpdateAdminInfoRoute();
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' ,  'auth:api']  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->defineCompanyAccountRoutes($routeRegistrar);
    }
}
