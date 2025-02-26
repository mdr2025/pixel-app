<?php

namespace PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Routes\PixelRouteManager;

class SignUpUsersAPIRoutesRegistrar extends PixelRouteRegistrar 
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

    protected function defineExportRoute() : void
    {
        Route::get('/signup-list/excel/export', [SignUpController::class, 'export']);
    }

    protected function defineChangeAccountStatusRoute() : void
    {
        Route::put('signup-list/status/{user}', [SignUpController::class, 'changeAccountStatus']);
    }

    protected function defineReverifyEmailRoute() : void
    {
        Route::get('signup-list/re-verify-email/{user}', [SignUpController::class, 'reVerifyEmail']);
    }

    protected function defineResendEmailVerificationRoute() : void
    {
        Route::post('signup-list/user/resend-verification-token-to-email', [SignUpController::class, 'resendVerificationTokenToUserEmail']);
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
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineIndexRoute(); 
            $this->defineShowRoute();
            $this->defineChangeEmailRoute();
            $this->defineResendEmailVerificationRoute();
            $this->defineReverifyEmailRoute();
            $this->defineChangeAccountStatusRoute();
            $this->defineExportRoute();
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

    protected function attachTenantMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $tenantMiddlewares = PixelRouteManager::getTenantMiddlewares();
        $routeRegistrar->middleware($tenantMiddlewares);
    }
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initMainApiRouteRegistrar();
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
