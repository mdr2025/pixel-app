<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists\BranchesController;
use PixelApp\Routes\PixelRouteManager;

class BranchesRouteRegistrar extends PixelRouteRegistrar 
{

    public function registerRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  PixelRouteManager::isItTenantApp() || PixelRouteManager::isItMonolithTenancyApp()  )
        {
            $this->defineTenantAppRoutes(); 
        } 
    }

    protected function defineChildBranchesListingRoute() : void
    {
        Route::get('system-configs/branches/list-children', [BranchesController::class, 'listChildrenBranches'])->middleware('throttle:6,1');
    }

    protected function defineSubBranchesListingRoute() : void
    {
        Route::get('list/sub-branches', [BranchesController::class, 'subBranches']);
    }

    protected function defineBranchesListingRoute() : void
    {
        Route::get('list/branches', [BranchesController::class, 'list']);
    }

    protected function defineBranchesResourceRoute() : void
    {
        Route::resource('branches', BranchesController::class)->parameters(["branches" => "branch"]);
    }

    protected function defineBranchesRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineBranchesResourceRoute(); 
            $this->defineBranchesListingRoute(); 
            $this->defineSubBranchesListingRoute();
            $this->defineChildBranchesListingRoute();
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
    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();
       $this->attachTenantMiddlewares($routeRegistrar);
       $this->defineBranchesRoutes($routeRegistrar);
    } 
     
}