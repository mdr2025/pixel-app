<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists\AreasController;
use PixelApp\Routes\PixelRouteManager;

class AreasRouteRegistrar extends PixelRouteRegistrar 
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

    protected function defineImportableFormatDownloadingRoute() : void
    {
        Route::get('system-configs/download-file-format/areas', [AreasController::class, 'importableFormalDownload']);
    }
   
    protected function defineImportingRoute() : void
    {
        Route::get('system-configs/areas/import', [AreasController::class, 'import']);
    }
    
    protected function defineExportingRoute() : void
    {
        Route::get('system-configs/areas/export', [AreasController::class, 'export']);
    }

    protected function defineAreasListingRoute() : void
    {
        Route::get('list/areas', [AreasController::class, 'list']);
    }
  
    protected function defineAreasResourceRoute() : void
    {
        Route::resource('system-configs/areas', AreasController::class)->parameters(["areas" => "area"]);
    }

    protected function defineAreasRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineAreasResourceRoute(); 
            $this->defineAreasListingRoute(); 
            $this->defineExportingRoute();
            $this->defineImportingRoute();
            $this->defineImportableFormatDownloadingRoute();
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 'auth:api'] ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->defineAreasRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);
       
       $this->defineAreasRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain);
        
        $this->defineAreasRoutes($routeRegistrar);
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