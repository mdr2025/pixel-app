<?php

namespace PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers\CompanyAuthClientController;
use PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers\CompanyAuthServerController;
use PixelApp\Routes\PixelRouteManager;

class CompanyAuthenticationAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function registerRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( PixelRouteManager::isItMonolithTenancyApp()  )
        {
            $this->defineMonolithTenancyAppRoutes(); 

        }elseif( PixelRouteManager::isItTenantApp()  )
        {
            $this->defineTenantAppRoutes();

        }elseif(PixelRouteManager::isItAdminPanelApp())
        {
            $this->defineAdminPanelAppRoutes(); 
        } 
    } 

    protected function defineCheckCrNoServerRoute() : void
    {
        Route::get('check-cr-no/{cr}', [CompanyAuthServerController::class , 'checkCrNo']);
    }
    protected function defineCheckCrNoClientRoute() : void
    {
        Route::get('check-cr-no/{cr}', [CompanyAuthClientController::class , 'checkCrNo']);
    }

    protected function defineCheckSubdomainServerRoute() : void
    {
        Route::get('check-subdomain/{domain}', [CompanyAuthServerController::class , 'checkSubDomain'] );
    }
    protected function defineCheckSubdomainClientRoute() : void
    {
        Route::get('check-subdomain/{domain}', [CompanyAuthClientController::class , 'checkSubDomain'] );
    }
    protected function defineCompanyClientCheckStatusRoute() : void
    {
        Route::post('company/check/status', [CompanyAuthClientController::class , 'checkStatus'])->middleware('reqLimit') ;
    }

    protected function defineCompanyServerCheckStatusRoute() : void
    {
        Route::post('company/check/status', [CompanyAuthServerController::class , 'checkStatus'])->middleware('reqLimit') ;
    }
 
    protected function defineCompanyClientEmailVerificationRoute() : void
    {
        Route::post('company/verify-email', [CompanyAuthClientController::class , 'verifyDefaultAdminEmail']);
    }

    protected function defineCompanyDefaultAdminUpdatingServerRoute() : void
    {
        Route::post('company/update-default-admin-info', [CompanyAuthServerController::class , 'updateDefaultAdminInfo']);
    }
  
    protected function defineCompanyServerEmailVerificationRoute() : void
    {
        Route::post('company/verify-email', [CompanyAuthServerController::class , 'verifyDefaultAdminEmail']);
    }
  
    
    protected function defineCompanyClientForgetIdRoute() : void
    {
        Route::post('company/forget-id', [CompanyAuthClientController::class , 'forgetId'])->middleware('reqLimit') ;
    }

    protected function defineCompanyServerForgetIdRoute() : void
    {
        Route::post('company/forget-id', [CompanyAuthServerController::class , 'forgetId'])->middleware('reqLimit') ;
    }
 
    protected function defineCompanyClientLoginRoute() : void
    {
        Route::post('company/login', [CompanyAuthClientController::class , 'login'])->middleware('reqLimit') ;
    }

    protected function defineCompanyServerLoginRoute() : void
    {
        Route::post('company/login', [CompanyAuthServerController::class , 'login'])->middleware('reqLimit') ;
    }

    protected function defineCompanyClientRegisteringRoute() : void
    {
        Route::post('company/register', [CompanyAuthClientController::class , 'register']) ;
    }

    protected function defineCompanyServerRegisteringRoute() : void
    {
        Route::post('company/register', [CompanyAuthServerController::class , 'register']) ;
    }

    protected function definefetchCompanyRoute() : void
    {
        Route::post('company/fecth-company', [CompanyAuthServerController::class , 'fetchCompany']) ;
    }

    protected function defineCompanyServerRoutes(?string $domain = null) : void
    {
        $routeRegistrar = $this->initCompanyRouteRegistrar();
        $this->attachCompanyGlobalMiddlewares($routeRegistrar);

        if($domain)
        {
            $routeRegistrar->domain($domain);
        }
        
        $routeRegistrar->group(function()
        {
           $this->defineCompanyServerLoginRoute();
           $this->defineCompanyServerRegisteringRoute();
           $this->defineCompanyServerForgetIdRoute();
           $this->defineCompanyServerEmailVerificationRoute();
           $this->defineCompanyServerCheckStatusRoute();
           $this->defineCheckSubdomainServerRoute();
           $this->defineCheckCrNoServerRoute();
           $this->definefetchCompanyRoute();
        });
    }

    protected function defineCompanyClientRoutes( string $domain ) : void
    {
        $routeRegistrar = $this->initCompanyRouteRegistrar();
        $this->attachCompanyGlobalMiddlewares($routeRegistrar);
        $routeRegistrar->domain($domain); 
        
        $routeRegistrar->group(function()
        {
           $this->defineCompanyClientLoginRoute();
           $this->defineCompanyClientRegisteringRoute();
           $this->defineCompanyClientForgetIdRoute();
           $this->defineCompanyClientEmailVerificationRoute();
           $this->defineCompanyClientCheckStatusRoute();
           $this->defineCheckSubdomainClientRoute();
           $this->defineCheckCrNoClientRoute();
        });
    }
    
    protected function attachCompanyGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( 'api' );
    }

    protected function initCompanyRouteRegistrar() : RouteRegistrar
    {
        return Route::prefix('api');
    }

    protected function defineAdminPanelAppRoutes() : void
    {
        $this->defineCompanyServerRoutes();
    }
    protected function defineTenantAppRoutes() : void
    {
        foreach(PixelRouteManager::getCentralDomains() as $domain)
        {
            $this->defineCompanyClientRoutes($domain);
        }
    }
    protected function defineMonolithTenancyAppRoutes() : void
    {
        foreach(PixelRouteManager::getCentralDomains() as $domain)
        {
            $this->defineCompanyServerRoutes($domain);
        }
    }
    
     
}
