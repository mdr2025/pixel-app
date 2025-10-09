<?php


namespace PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars;

 
use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists\BranchesController;
use PixelApp\Routes\PixelRouteBootingManager;
 

class BranchesRouteRegistrar extends PixelRouteRegistrar 
{

    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if(  PixelRouteBootingManager::isBootingForTenantApp() 
             ||
             PixelRouteBootingManager::isBootingForMonolithTenancyApp()  )
        {
            $this->defineTenantAppRoutes(); 
        } else
        {

            $this->defineNormalAppRoutes();
        }
    }

    
    public function isFuncAvailableToDefine() : bool
    {
        return $this->initPixelRoutesInstallingManager()
                    ->getPixelAppSystemRequirementsCard()?->isBranchesFuncRequired() 
                    ?? false;
    }

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["pixel-app-package-route-registrars"]["dropdown-list"]["branches"] = static::class;
    }

    protected function defineImportableFormatDownloadingRoute() : void
    {
        Route::get('system-configs/download-file-format/branches', [BranchesController::class, 'importableFormalDownload']);
    }
   
    protected function defineImportingRoute() : void
    {
        Route::get('system-configs/branches/import', [BranchesController::class, 'import']);
    }
    
    protected function defineExportingRoute() : void
    {
        Route::get('system-configs/branches/export', [BranchesController::class, 'export']);
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
        $routeRegistrar->group(function()
        {
            $this->defineBranchesResourceRoute(); 
            $this->defineBranchesListingRoute(); 
            $this->defineSubBranchesListingRoute();
            $this->defineChildBranchesListingRoute();
            $this->defineImportableFormatDownloadingRoute();
            $this->defineImportingRoute();
            $this->defineExportingRoute();
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

       $this->defineBranchesRoutes($routeRegistrar);
    } 

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineBranchesRoutes($routeRegistrar);
    } 
     
}