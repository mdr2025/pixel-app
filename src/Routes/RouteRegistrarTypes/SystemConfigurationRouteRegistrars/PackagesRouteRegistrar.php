<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class PackagesRouteRegistrar extends PixelRouteRegistrar 
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
        //     $this->defineAdminPanelAppRoutes(); 
        // } 
    }

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["pixel-app-package-route-registrars"]["packages"] = static::class;
    }

    protected function definePackagesListingServerRoute() : void
    {
        //Route::get('list/Packages', [PackagesController::class, 'list']);
    }
  
    protected function definePackagesListingClientRoute() : void
    {
        //Route::get('packages', [PackageController::class, 'list']);
    }

    protected function definePackagesServerRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->definePackagesListingServerRoute();  
        });
    }
    protected function definePackagesClientRoutes(RouteRegistrar $routeRegistrar ) : void
    { 
        $routeRegistrar->group(function()
        {
            $this->definePackagesListingClientRoute();  
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 'auth:api']  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }
 
    protected function defineAdminPanelAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->definePackagesServerRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->definePackagesClientRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain);
        
        $this->definePackagesServerRoutes($routeRegistrar);
    }

    protected function defineMonolithTenancyAppRoutes() : void
    {
        foreach(PixelRouteManager::getCentralDomains() as $domain)
        {
            $this->defineCentralDomainRoutes($domain);
        }
    }
    
     
}