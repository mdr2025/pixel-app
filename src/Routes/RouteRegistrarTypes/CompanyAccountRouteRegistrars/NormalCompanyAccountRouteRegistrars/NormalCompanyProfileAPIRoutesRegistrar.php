<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\NormalCompanyAccountControllers\NormalCompanyAccountController;
use PixelApp\Routes\PixelRouteManager;

class NormalCompanyProfileAPIRoutesRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( 
            PixelRouteManager::isItMonolithTenancyApp()  
            ||
            PixelRouteManager::isItAdminPanelApp()
            ||
            PixelRouteManager::isItNormalApp()  
          )
        {
            $this->defineNormalAppRoutes(); 
        }
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
        return [ 'api' , 
        
        /**
         * @todo to check this later for auth for microservices case for admin panel auth
         * you maybe need to remove it for admin panel and finding another solution for auth between client and server
         */
        'auth:api'
        ]  ;
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
