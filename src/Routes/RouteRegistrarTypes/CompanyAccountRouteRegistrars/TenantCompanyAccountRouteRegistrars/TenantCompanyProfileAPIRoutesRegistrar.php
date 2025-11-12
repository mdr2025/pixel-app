<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\TenantCompanyAccountControllers\UserCompanyAccountClientController;
use PixelApp\Http\Controllers\CompanyAccountControllers\TenantCompanyAccountControllers\UserCompanyAccountServerController;
use PixelApp\Routes\PixelRouteBootingManager;

class TenantCompanyProfileAPIRoutesRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( PixelRouteBootingManager::isBootingForMonolithTenancyApp()  )
        {
            $this->defineMonolithTenancyAppRoutes(); 

        }elseif( PixelRouteBootingManager::isBootingForTenantApp()  )
        {
            $this->defineTenantAppRoutes();

        }else
        {
            $this->defineAdminPanelRoutes(); 
        } 
    }

   public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
   {
       $arrayToAppend["pixel-app-package-route-registrars"]["tenant-company-profile"] = static::class;
   }
  
    protected function defineUpdateCompanyProfileServerRoute() : void
    {
        Route::post('profile/update', [UserCompanyAccountServerController::class, 'updateCompanyProfile']);
    }

    protected function defineUpdateCompanyProfileClientRoute() : void
    {
        Route::post('profile/update', [UserCompanyAccountClientController::class, 'updateCompanyProfile']);
        
    }

    protected function defineCompanyProfileServerRoute() : void
    { 
        Route::get('profile', [UserCompanyAccountServerController::class, 'companyProfile']);
    }
 

    protected function defineCompanyProfileClientRoute() : void
    { 
        Route::get('profile', [UserCompanyAccountClientController::class, 'companyProfile']);
    }

    protected function defineUpdateAdminInfoRoute() : void
    {    
        Route::put('profile/update-admin', [UserCompanyAccountClientController::class, 'changeDefaultAdmin']);
    }

    protected function defineCompanyProfileClientRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineCompanyProfileClientRoute(); 
            $this->defineUpdateCompanyProfileClientRoute(); 
            $this->defineUpdateAdminInfoRoute();
        });
    }

    protected function defineCompanyProfileServerRoutes(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineCompanyProfileServerRoute(); 
            $this->defineUpdateCompanyProfileServerRoute(); 
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' ]  ;
    }

    protected function getTenantRouteMiddlewares() : array
    {
        $tenantMiddlewares = parent::getTenantRouteMiddlewares();
        $tenantMiddlewares[] = 'auth:api';

        return $tenantMiddlewares;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/company');
    }
 
    protected function defineAdminPanelRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachServerRouteMiddlewares($routeRegistrar);

        $this->defineCompanyProfileServerRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineCompanyProfileClientRoutes($routeRegistrar);
    }
 
    protected function defineMonolithTenancyAppRoutes() : void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachTenantMiddlewares($routeRegistrar);
        
        $this->defineCompanyProfileServerRoutes($routeRegistrar , false);
    }
    
     
}
