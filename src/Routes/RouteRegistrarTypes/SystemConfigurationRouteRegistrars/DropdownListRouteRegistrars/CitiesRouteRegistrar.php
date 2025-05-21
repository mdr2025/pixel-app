<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists\CitiesController;
use PixelApp\Routes\PixelRouteManager;

class CitiesRouteRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
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

    
    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["dropdown-list"]["cities"] = static::class;
    }

    protected function defineCitiesListingRoute() : void
    {
        Route::get('list/cities', [CitiesController::class, 'list']);
    }
  
    protected function defineCitiesResourceRoute() : void
    {
        Route::resource('system-configs/cities', CitiesController::class)->parameters(["cities" => "city"]);
    }

    protected function defineCitiesRoutes(RouteRegistrar $routeRegistrar ) : void
    { 
        $routeRegistrar->group(function()
        {
            $this->defineCitiesResourceRoute(); 
            $this->defineCitiesListingRoute(); 
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
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->defineCitiesRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineCitiesRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
 
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain);

        $this->defineCitiesRoutes($routeRegistrar);
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