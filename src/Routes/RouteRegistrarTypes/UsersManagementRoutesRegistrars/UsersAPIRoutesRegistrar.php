<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UsersManagementControllers\UserController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class UsersAPIRoutesRegistrar extends PixelRouteRegistrar 
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
            $this->defineNormalAppRoutes(); 
        } 
    }
    
    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["pixel-app-package-route-registrars"]["users-list-management"] = static::class;
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
        Route::get('list/users-emails', [UserController::class, 'listDefaultUsers']);
    }
    protected function defineUserBranchRoutes() : void
    {
        Route::get('list/accessible-branches', [UserController::class , 'getAccessibleBranchesAndPrimaryBranchFromUser']);
        Route::get('list/primary-branch', [UserController::class , 'getPrimaryBranchFromUser']);
        Route::get('list/primary-and-filtered-branches', [UserController::class , 'getPrimaryBranchAndFilteredBranches']);
        Route::get('list/users-by-branch', [UserController::class , 'getFilteredUsersByBranch']);
    }

    protected function defineExportRoute() : void
    {
        Route::get('users-list/excel/export', [UserController::class, 'export']);
    }

    protected function defineChangeAccountStatusRoute() : void
    {
        Route::post('users-list/status/{user}', [UserController::class, 'changeAccountStatus']);
    }
 
    protected function defineChangeEmailRoute() : void
    {
        Route::post('users-list/change-email/{user}', [UserController::class, 'changeEmail']);
    }

    protected function defineUpdateRoute() : void
    { 
        Route::post('users-list/update/{user}', [UserController::class, 'update']);
    }
  
    protected function defineResourceRoute() : void
    {
        Route::resource('users-list', UserController::class)
             ->parameters(['users-list'=>'user'])
             ->except("destroy", "update");
    }

    protected function defineUsersRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineResourceRoute(); 
            $this->defineUpdateRoute();
            $this->defineChangeEmailRoute();
            $this->defineChangeAccountStatusRoute();
            $this->defineExportRoute();
            $this->defineDefaultUsersListingRoute();
            $this->defineUserBranchRoutes();
            $this->defineUsersListingRoute();
            $this->definResbonsiablePersonsListingRoute();
            $this->definEmployeesListingUsersRoute();
        });
    }
    
    

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }

    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 'auth:api']  ;
    }

    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);

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
        
        $this->attachGlobalMiddlewares($routeRegistrar);
        
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
