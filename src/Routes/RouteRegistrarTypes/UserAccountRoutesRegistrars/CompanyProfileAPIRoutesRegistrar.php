<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UserAccountControllers\UserCompanyAccountClientController;
use PixelApp\Http\Controllers\UserAccountControllers\UserCompanyAccountServerController;
use PixelApp\Routes\PixelRouteManager;

class CompanyProfileAPIRoutesRegistrar extends PixelRouteRegistrar 
{

    public function registerRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( PixelRouteManager::isItMonolithTenancyApp()  )
        {
            $this->defineMonolithTenancyAppRoutes(); 

        }elseif( PixelRouteManager::isItTenantApp()  )
        {
            $this->defineTenantAppRoutes();

        }else
        {
            $this->defineAdminPanelRoutes(); 
        } 
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

    protected function defineCompanyProfileClientRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineCompanyProfileClientRoute(); 
            $this->defineUpdateCompanyProfileClientRoute(); 
        });
    }
    protected function defineCompanyProfileServerRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineCompanyProfileServerRoute(); 
            $this->defineUpdateCompanyProfileServerRoute(); 
        });
    }
    
    protected function attachGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( [ 'api' , 
        
        /**
         * @todo to check this later for auth for microservices case for admin panel auth
         * you maybe need to remove it for admin panel and finding another solution for auth between client and server
         */
        'auth:api'
        ] );
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

    protected function defineAdminPanelRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
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
        $this->defineCompanyProfileServerRoutes($routeRegistrar);
    }
    
     
}
