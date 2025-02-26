<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Routes\PixelRouteManager;

class PackagesRouteRegistrar extends PixelRouteRegistrar 
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
            $this->defineAdminPanelAppRoutes(); 
        } 
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
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->definePackagesListingServerRoute();  
        });
    }
    protected function definePackagesClientRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->definePackagesListingClientRoute();  
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
    protected function defineAdminPanelAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
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