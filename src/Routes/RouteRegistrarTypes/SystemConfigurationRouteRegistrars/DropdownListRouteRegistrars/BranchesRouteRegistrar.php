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
        Route::get('system-configs/download-file-format/branches', [BranchesController::class, 'downloadFileFormat']);
    }
   
    protected function defineImportingRoute() : void
    {
        Route::post('system-configs/import/branches', [BranchesController::class, 'import']);
    }
    
    protected function defineExportingRoute() : void
    {
        Route::get('system-configs/excel/export/branches', [BranchesController::class, 'export']);
    }

    protected function defineBranchTeamRoutes() : void
    {
        Route::get('system-configs/branches-teams', [BranchesController::class, 'indexBranchTeams']);
        Route::post('system-configs/branches-teams', [BranchesController::class, 'addTeam']);
    }
    protected function defineBranchesMainFuncinalityRoutes() : void
    {
        Route::prefix('system-configs/branches')
            ->controller(BranchesController::class)
            ->group(function () 
            {
                Route::get('main-branch', 'getFirstParentBranch')->withoutMiddleware("throttle:api")->middleware('throttle:6,1');
                Route::get('list', 'listBranches');
                Route::get('sub-branches', 'subBranches');
                Route::post('/{branch}', 'update');
                Route::post('/', 'store');
                Route::delete('/', 'destroy');
                Route::get('/', 'index');
            });
    }

    protected function defineBranchesRoutes(RouteRegistrar $routeRegistrar ) : void
    {  
        $routeRegistrar->group(function()
        {
            $this->defineBranchesMainFuncinalityRoutes(); 
            $this->defineImportableFormatDownloadingRoute();
            $this->defineImportingRoute();
            $this->defineExportingRoute();

            $this->defineBranchTeamRoutes();
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