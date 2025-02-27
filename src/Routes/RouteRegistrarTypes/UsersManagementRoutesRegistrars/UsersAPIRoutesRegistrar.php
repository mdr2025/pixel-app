<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UsersManagementControllers\UserController;
use PixelApp\Routes\PixelRouteManager;

class UsersAPIRoutesRegistrar extends PixelRouteRegistrar 
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
            $this->defineNormalAppRoutes(); 
        } 
    }
    
    
    protected function definEmployeesListingUsersRoute()   : void
    {
        Route::get('list/employees', [UserController::class, 'list']); 
    }
    protected function definResbonsiablePersonsListingRoute()   : void
    {
        Route::get('list/responsible-persons', [UserController::class, 'list']);
    }
    protected function defineUsersListingRoute() : void
    {
        Route::get('list/users', [UserController::class, 'list']);
    }
    protected function defineDefaultUsersListingRoute() : void
    {
        Route::get('list/users-emails', [UserController::class, 'listDefaultUser']);
    }
    protected function defineExportRoute() : void
    {
        Route::get('users-list/excel/export', [UserController::class, 'export']);
    }

    protected function defineChangeAccountStatusRoute() : void
    {
        Route::put('users-list/status/{user}', [UserController::class, 'changeAccountStatus']);
    }
 
    protected function defineChangeEmailRoute() : void
    {
        Route::post('users-list/change-email/{user}', [UserController::class, 'changeEmail']);
    }

    protected function defineUpdateRoute() : void
    { 
        Route::put('users-list/update/{user}', [UserController::class, 'update']);
    }
  
    protected function defineResourceRoute() : void
    {
        Route::resource('users-list', UserController::class)->parameters(['users-list'=>'user'])->except("destroy", "update");
    }

    protected function defineUsersRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineResourceRoute(); 
            $this->defineUpdateRoute();
            $this->defineChangeEmailRoute();
            $this->defineChangeAccountStatusRoute();
            $this->defineExportRoute();
            $this->defineDefaultUsersListingRoute();
            $this->defineUsersListingRoute();
            $this->definResbonsiablePersonsListingRoute();
            $this->definEmployeesListingUsersRoute();
        });
    }
    
    protected function attachGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( [ 'api' , 'auth:api'] );
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }

    protected function attachTenantMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $tenantMiddlewares = PixelRouteManager::getTenantMiddlewares();
        $routeRegistrar->middleware($tenantMiddlewares);
    }
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        $this->defineUsersRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();
       $this->attachTenantMiddlewares($routeRegistrar);
       $this->defineUsersRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        $routeRegistrar->domain($domain);
        $this->defineUsersRoutes($routeRegistrar);
    }

    protected function defineMonolithTenancyAppRoutes() : void
    {
        foreach(PixelRouteManager::getCentralDomains() as $domain)
        {
            $this->defineCentralDomainRoutes($domain);
        }

        $this->defineTenantAppRoutes();
    }
    
     
}
