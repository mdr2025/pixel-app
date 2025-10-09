<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\NormalCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\NormalCompanyAccountControllers\NormalCompanyAccountController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class NormalCompanyProfileAPIRoutesRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( 
            PixelRouteBootingManager::isBootingForAdminPanelApp()
            ||
            PixelRouteBootingManager::isBootingForNormalApp()  
          )
        {
            $this->defineNormalAppRoutes(); 
        
        }elseif(PixelRouteBootingManager::isBootingForMonolithTenancyApp()  )
        {
            $this->defineMonolithTenancyAppRoutes(); 
        }
    }

   public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
   {
       $arrayToAppend["pixel-app-package-route-registrars"]["normal-company-profile"] = static::class;
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

    protected function defineMonolithTenancyAppRoutes() : void
    {
        $centralDomain = PixelRouteManager::getCentralDomains();

        foreach($centralDomain as $domain)
        {
            
            $routeRegistrar = $this->initMainApiRouteRegistrar();

            $routeRegistrar->domain($domain);
            
            $this->attachGlobalMiddlewares($routeRegistrar);

            $this->defineCompanyAccountRoutes($routeRegistrar);
        }
    }
}
