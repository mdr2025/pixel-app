<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;


/**
 * this functinality is commented in our programs for now
 */
class CompanyBranchesAPIRoutesRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        // if( PixelRouteBootingManager::isBootingForMonolithTenancyApp()  )
        // {
        //     $this->defineMonolithTenancyAppRoutes(); 

        // }elseif( PixelRouteBootingManager::isBootingForTenantApp()  )
        // {
        //     $this->defineTenantAppRoutes();

        // }else
        // {
        //     $this->defineAdminPanelRoutes(); 
        // } 
    }

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        // $arrayToAppend["pixel-app-package-route-registrars"]["user-account-company-branches"] = static::class;
    }

    // protected function defineCompanyBranchesListingServerRoute() : void
    // {
    //     Route::get('branches-list' , [UserCompanyAccountServerController::class,'companyBranchList']);
    // }

    // protected function defineCompanyBranchesListingClientRoute() : void
    // {
    //     Route::get('branches-list' , [UserCompanyAccountClientController::class,'companyBranchList']);
    // }

    // protected function defineBranchStatusEditingServerRoute() : void
    // { 
    //     Route::post('edit-branch-status/{id}', [UserCompanyAccountServerController::class, 'changeBranchStatus']);
    // }

    // protected function defineBranchStatusEditingClientRoute() : void
    // { 
    //     Route::post('edit-branch-status/{id}', [UserCompanyAccountClientController::class, 'changeBranchStatus']);
    // }
 
    // protected function defineCompanyBranchesServerRoutes(RouteRegistrar $routeRegistrar ) : void
    // {
    //     $routeRegistrar->group(function()
    //     {
    //         $this->defineBranchStatusEditingServerRoute(); 
    //         $this->defineCompanyBranchesListingServerRoute(); 
    //     });
    // }

    // protected function defineCompanyBranchesClientRoutes(RouteRegistrar $routeRegistrar ) : void
    // {
    //     $routeRegistrar->group(function()
    //     {
    //         $this->defineBranchStatusEditingClientRoute(); 
    //         $this->defineCompanyBranchesListingClientRoute(); 
    //     });
    // }

    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 
        
        /**
         * @todo same note for admin microservice auth
         */
        'auth:api'
        ] ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/company');
    }
 
    protected function defineAdminPanelRoutes() : void
    {
        // $routeRegistrar = $this->initMainApiRouteRegistrar();
 
        // $this->attachGlobalMiddlewares($routeRegistrar);

        // $this->defineCompanyBranchesServerRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
    //    $routeRegistrar = $this->initMainApiRouteRegistrar();

    //    $this->attachTenantMiddlewares($routeRegistrar);

    //    $this->defineCompanyBranchesClientRoutes($routeRegistrar);
    }
 
    protected function defineMonolithTenancyAppRoutes() : void
    { 
        // $routeRegistrar = $this->initMainApiRouteRegistrar();

        // $this->attachTenantMiddlewares($routeRegistrar);

        // $this->defineCompanyBranchesServerRoutes($routeRegistrar);
    }
}
