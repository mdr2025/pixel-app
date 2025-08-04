<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UserAccountControllers\SignatureController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class UserSignatureAPIRoutesRegistrar extends PixelRouteRegistrar 
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
                    ->getPixelAppSystemRequirementsCard()?->isUserSignatureFuncRequired() 
                    ?? false;
    }

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["user-signature"] = static::class;
    }

    protected function defineSignatureUpdatingRoute() : void
    { 
        Route::post('signature-edit',[SignatureController::class ,'update']);
    }
    protected function defineSignatureShowRoute() : void
    {
        Route::get('signature',[SignatureController::class ,'show']);
    }
    protected function defineSignatureResourceRoute() : void
    {
        Route::resource('signature',SignatureController::class)->except(['show','update']);
    }

    protected function defineUserSignatureRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineSignatureResourceRoute(); 
            $this->defineSignatureShowRoute();
            $this->defineSignatureUpdatingRoute(); 
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 'auth:api']  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/user');
    }
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->defineUserSignatureRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineUserSignatureRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain);

        $this->defineUserSignatureRoutes($routeRegistrar);
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
