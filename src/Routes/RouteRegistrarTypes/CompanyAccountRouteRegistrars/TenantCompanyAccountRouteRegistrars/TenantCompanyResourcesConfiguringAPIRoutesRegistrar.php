<?php

namespace PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\CompanyAccountControllers\CompanySettingsControllers\TenantCompanySettingsControllers\TenantCompanyResourcesConfiguringController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class TenantCompanyResourcesConfiguringAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  PixelRouteBootingManager::isBootingForTenantApp() 
             ||
             PixelRouteBootingManager::isBootingForMonolithTenancyApp() )
        {
            $this->defineTenantAppCentralDomainRoutes();

        }elseif(PixelRouteBootingManager::isBootingForAdminPanelApp())
        {
            $this->defineAdminPanelRoutes();
        }
    }
    
   public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
   {
       $arrayToAppend["pixel-app-package-route-registrars"]["tenant-company-resources-configuring"] = static::class;
   }

    //from admin panel to tenant app central domain
    protected function defineTenantCompanyResourcesConfiguringRoute() : void
    { 
        Route::post('configure-resources', [TenantCompanyResourcesConfiguringController::class, 'configureTenantResources']);
    }
 
    
    //from tenant app central domain to admin panel
    protected function defineTenantCompanyResourcesConfiguringCancelingRoute() : void
    {
        Route::post('cancel-resources-configuring', [TenantCompanyResourcesConfiguringController::class, 'cancelTenantResourcesConfiguring']);
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api'  ]  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/company');
    }

    protected function defineTenantAppCentralDomainRoutes() : void
    {
        foreach(PixelRouteManager::getCentralDomains() as $domain)
        {
            $routeRegistrar = $this->initMainApiRouteRegistrar();
       
            $this->attachServerRouteMiddlewares($routeRegistrar);

            $routeRegistrar->domain($domain);

            $routeRegistrar->group(function()
            {
                    $this->defineTenantCompanyResourcesConfiguringRoute();
            });
        }
       
    }
     
    protected function defineAdminPanelRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();
       
       $this->attachServerRouteMiddlewares($routeRegistrar);

       $routeRegistrar->group(function()
       {
            $this->defineTenantCompanyResourcesConfiguringCancelingRoute();
       });
    }
}
