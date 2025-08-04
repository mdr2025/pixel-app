<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

use PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists\DepartmentsController;
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class DepartmentRouteRegistrar extends PixelRouteRegistrar 
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
                    ->getPixelAppSystemRequirementsCard()?->isDepartmentsFuncRequired() 
                    ?? false;
    }
 
    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["dropdown-list"]["departmens"] = static::class;
    }
    
    protected function defineDepartmentsListingRoute() : void
    {
        Route::get('list/departments', [DepartmentsController::class, 'list']); 
    }
    protected function defineDepartmentsExportingRoute() : void
    {
        Route::get('system-configs/departments/export', [DepartmentsController::class, 'export']);
    }

    protected function defineDepartmentsImportingRoute() : void
    {
        Route::post('system-configs/departments/import', [DepartmentsController::class, 'import']);
    }

    protected function defineDepartmentFileFormatDownloadingRoute() : void
    {
        Route::get('system-configs/download-file-format/departments', [DepartmentsController::class, 'importableFormalDownload']);
    }
    protected function defineDepartmentsResourceRoute() : void
    {
        Route::resource('system-configs/departments', DepartmentsController::class)->parameters(["departments" => "department"]);
    }

    protected function defineDepartmentsRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineDepartmentsResourceRoute(); 
            $this->defineDepartmentFileFormatDownloadingRoute();
            $this->defineDepartmentsImportingRoute(); 
            $this->defineDepartmentsExportingRoute(); 
            $this->defineDepartmentsListingRoute();  
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

        $this->defineDepartmentsRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);
       
       $this->defineDepartmentsRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);
        
        $routeRegistrar->domain($domain);
        
        $this->defineDepartmentsRoutes($routeRegistrar);
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