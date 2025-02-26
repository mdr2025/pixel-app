<?php

namespace PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\AuthenticationControllers\UserAuthenticationControllers\AuthController;
use PixelApp\Routes\PixelRouteManager;

class UserAuthenticationAPIRoutesRegistrar extends PixelRouteRegistrar 
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

    protected function defineUserMeRoute() : void
    {
        Route::get('me', [AuthController::class, 'getLoggedUser'])->middleware('auth:api');
    }
    protected function defineUserLogoutRoute() : void
    {
        Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    }
    protected function defineUserRefreshingTokenRoute() : void
    {
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    }
    protected function defineUserEmailVerificationRoute() : void
    {
        Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    }

    protected function defineUserResetPasswordRoute() : void
    {
        Route::post('reset-password', [AuthController::class, 'resetPassword']);
    }

    protected function defineUserForgetPasswordRoute() : void
    {
        Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    }

    protected function defineUserLoginRoute() : void
    {
        Route::post('login', [AuthController::class, 'login'])->middleware("reqLimit");
    }
 

    protected function defineUserRegisteringRoute() : void
    {
        Route::post('register', [AuthController::class, 'register'])->middleware("reqLimit");
    }

    protected function defineUserAuthRoutes(RouteRegistrar $routeRegistrar ) : void
    {
        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->group(function()
        {
            $this->defineUserRegisteringRoute();
            $this->defineUserLoginRoute();
            $this->defineUserForgetPasswordRoute();
            $this->defineUserResetPasswordRoute();
            $this->defineUserEmailVerificationRoute();
            $this->defineUserRefreshingTokenRoute();
            $this->defineUserLogoutRoute();
            $this->defineUserMeRoute();
        });
    }
    
    protected function attachGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( 'api' );
    }

    protected function initUserAuthRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api/auth');
    }

    protected function attachTenantMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $tenantMiddlewares = PixelRouteManager::getTenantMiddlewares();
        $routeRegistrar->middleware($tenantMiddlewares);
    }
    protected function defineNormalAppRoutes() : void
    {
        $routeRegistrar = $this->initUserAuthRouteRegistrar();
        $this->defineUserAuthRoutes($routeRegistrar);
    }

    protected function defineTenantAppRoutes() : void
    {
       $routeRegistrar = $this->initUserAuthRouteRegistrar();
       $this->attachTenantMiddlewares($routeRegistrar);
       $this->defineUserAuthRoutes($routeRegistrar);
    }

    protected function defineCentralDomainRoutes(string $domain) :void
    { 
        $routeRegistrar = $this->initUserAuthRouteRegistrar();
        $routeRegistrar->domain($domain);
        $this->defineUserAuthRoutes($routeRegistrar);
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
