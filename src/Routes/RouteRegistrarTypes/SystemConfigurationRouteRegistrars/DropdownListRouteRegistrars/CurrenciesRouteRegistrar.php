<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists\CurrenciesController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class CurrenciesRouteRegistrar extends PixelRouteRegistrar 
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
 
    
    public function isFuncAvailableToDefine() : bool
    {
        return $this->initPixelRoutesInstallingManager()
                    ->getPixelAppSystemRequirementsCard()?->isCurrenciesFuncRequired() 
                    ?? false;
    }

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["pixel-app-package-route-registrars"]["dropdown-list"]["currencies"] = static::class;
    }
    
    protected function defineSetMainCurrencyRoute() : void
    {
        Route::post('currencies/set-main/{currency}', [CurrenciesController::class, 'setMainCurrency']);
    }

    protected function defineImportableFormatDownloadingRoute() : void
    {
        Route::get('system-configs/download-file-format/currencies', [CurrenciesController::class, 'importableFormalDownload']);
    }

    protected function defineImportingRoute() : void
    {
        Route::post('currencies/import', [CurrenciesController::class, 'import']);
    }
    
    protected function defineExportingRoute() : void
    {
        Route::post('currencies/export', [CurrenciesController::class, 'export']);
    }
    
    protected function defineCurrenciesListingRoute() : void
    {
        Route::get('list/currencies', [CurrenciesController::class, 'list']);
    }
  
    protected function defineCurrenciesResourceRoute() : void
    {
        Route::resource('currencies', CurrenciesController::class)->only(["index", "update"]);
        
        
    }

    protected function defineCurrenciesRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineCurrenciesResourceRoute(); 
            $this->defineCurrenciesListingRoute(); 
            $this->defineExportingRoute();
            $this->defineImportingRoute();
            $this->defineImportableFormatDownloadingRoute();
            $this->defineSetMainCurrencyRoute();
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

        $this->defineCurrenciesRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);
       
       $this->defineCurrenciesRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain);
        
        $this->defineCurrenciesRoutes($routeRegistrar);
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