<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UserAccountControllers\UserProfileController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class UserProfileAPIRoutesRegistrar extends PixelRouteRegistrar 
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

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["user-profile"] = static::class;
    }

    protected function defineChangePasswordRoute() : void
    {
        Route::put('change-password', [UserProfileController::class, 'changePassword']);
    }

    protected function defineUpdateProfileRoute() : void
    { 
        Route::put('update-profile', [UserProfileController::class, 'updateProfile']);
    }
  
    protected function defineProfileShowRoute() : void
    {
        Route::get('profile', [UserProfileController::class, 'profile']);
    }

    protected function defineUserProfileRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineProfileShowRoute(); 
            $this->defineUpdateProfileRoute();
            $this->defineChangePasswordRoute(); 
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return[ 'api' , 'auth:api']  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/user');
    }
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->defineUserProfileRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineUserProfileRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain);

        $this->defineUserProfileRoutes($routeRegistrar);
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
