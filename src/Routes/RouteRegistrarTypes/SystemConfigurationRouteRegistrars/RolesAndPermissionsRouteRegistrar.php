<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\SystemConfigurationControllers\RolesAndPermissions\PermissionController;
use PixelApp\Http\Controllers\SystemConfigurationControllers\RolesAndPermissions\RolesController;
use PixelApp\Routes\PixelRouteManager;

class RolesAndPermissionsRouteRegistrar extends PixelRouteRegistrar 
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
  
    protected function defineSwitchingRoleRoute() : void
    {
        Route::put('roles/{role}/status', [RolesController::class, 'switchRole']);
    }

    protected function defineListingAllRolesRoute() : void
    {
        Route::get('list/roles', [RolesController::class, 'listAllRoles']);
    }

    protected function defineListingDefaultRolesRoute() : void
    {
        Route::get('list/default-roles', [RolesController::class, 'listDefaultRoles']);
    }

    protected function defineGettingAllPermissionsRoute() : void
    {
        Route::get('all-permissions', [RolesController::class, 'allPermission']);
    }
    
    protected function defineRolesResourceRoute() : void
    {
        Route::resource('roles', RolesController::class)->except(['edit']);
    }
    protected function defineAddPermissionRoute() : void
    {
        Route::post('add/permission', [PermissionController::class, 'store']);
    }

    protected function defineRolesAndPermissionsRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineAddPermissionRoute(); 
            $this->defineRolesResourceRoute();
            $this->defineGettingAllPermissionsRoute(); 
            $this->defineListingAllRolesRoute(); 
            $this->defineListingDefaultRolesRoute(); 
            $this->defineSwitchingRoleRoute(); 

        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api'  , 'auth:api'
        ] ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);
       
        $this->defineRolesAndPermissionsRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();
     
       $this->attachTenantMiddlewares($routeRegistrar);
     
       $this->defineRolesAndPermissionsRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);
        
        $routeRegistrar->domain($domain);
        
        $this->defineRolesAndPermissionsRoutes($routeRegistrar);
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
