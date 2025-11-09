<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\UsersManagementControllers\SignUpController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class SignUpUsersAPIRoutesRegistrar extends PixelRouteRegistrar 
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
        $arrayToAppend["pixel-app-package-route-registrars"]["signup-users-management"] = static::class;
    }

    protected function defineExportRoute() : void
    {
        Route::get('signup-list/excel/export', [SignUpController::class, 'export']);
    }
    

    protected function defineReverifyEmailRoute() : void
    {
        Route::get('signup-list/re-verify-email/{user}', [SignUpController::class, 'reVerifyEmail']);
    }

    protected function defineResendEmailVerificationRoute() : void
    {
        Route::post('signup-list/user/resend-verification-token-to-email', [SignUpController::class, 'resendVerificationTokenToUserEmail']);
    }
    
    protected function defineRejectingSignupUserRoute() : void
    {
        Route::put('signup-list/reject/{user}', [SignUpController::class, 'rejectAccount']);
    }
    protected function defineApprovingSignupUserRoute() : void
    {
        Route::put('signup-list/approve/{user}', [SignUpController::class, 'approveAccount']);
    }

    protected function defineChangeEmailRoute() : void
    {
        Route::post('signup-list/change-email/{user}', [SignUpController::class, 'changeAccountEmail']);
    }

    protected function defineShowRoute() : void
    { 
        Route::get('signup-list/{user}' , [SignUpController::class , 'show']);
    }
  
    protected function defineIndexRoute() : void
    {
        Route::get('signup-list' , [SignUpController::class , 'index']);
    }

    protected function defineSignupUsersRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $routeRegistrar->group(function()
        {
            $this->defineIndexRoute(); 
            $this->defineShowRoute();
            $this->defineChangeEmailRoute();
            $this->defineResendEmailVerificationRoute();
            $this->defineReverifyEmailRoute();
            $this->defineApprovingSignupUserRoute();
            $this->defineRejectingSignupUserRoute();
            $this->defineExportRoute();
        });
    }
    
    protected function getGlobalMiddlewares() : array
    {
        return [ 'api' , 'auth:api']  ;
    }

    protected function initMainApiRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }
 
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
        
        $this->attachGlobalMiddlewares($routeRegistrar);

        $this->defineSignupUsersRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initMainApiRouteRegistrar();

       $this->attachTenantMiddlewares($routeRegistrar);

       $this->defineSignupUsersRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initMainApiRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);
        
        $routeRegistrar->domain($domain);

        $this->defineSignupUsersRoutes($routeRegistrar);
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
