<?php

namespace PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers\CompanyAuthClientController;
use PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers\CompanyAuthServerController;
use PixelApp\Routes\PixelRouteManager;

class CompanyAuthenticationAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
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

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["company-auth"] = static::class;
    }

    public function isFuncAvailableToDefine(PixelAppSystemRequirementsCard $requirementsCard) : bool
    {
        $requirementsCard->getSystemType() == 
        return true;
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
        Route::post('company/check/status', [CompanyAuthClientController::class , 'checkStatus']);
    }

    protected function defineCompanyServerCheckStatusRoute() : void
    {
        Route::post('company/check/status', [CompanyAuthServerController::class , 'checkStatus']);
    }
  
    protected function defineCompanyDefaultAdminUpdatingServerRoute() : void
    {
        Route::post('company/update-default-admin-info', [CompanyAuthServerController::class , 'updateDefaultAdminInfo']);
    }
  
    protected function defineCompanyDefaultAdminsyncingDataServerRoute() : void
    {
        Route::post('company/sync-default-admin-data', [CompanyAuthServerController::class , 'syncDefaultAdminData']);
    }

    protected function defineCompanyServerEmailVerificationRoute() : void
    {
        Route::post('company/verify-email', [CompanyAuthServerController::class , 'verifyDefaultAdminEmail']);
    }
  
    
    protected function defineCompanyClientForgetIdRoute() : void
    {
        Route::post('company/forget-id', [CompanyAuthClientController::class , 'forgetId']);
    }

    protected function defineCompanyServerForgetIdRoute() : void
    {
        Route::post('company/forget-id', [CompanyAuthServerController::class , 'forgetId']);
    }
 
    protected function defineCompanyClientLoginRoute() : void
    {
        Route::post('company/login', [CompanyAuthClientController::class , 'login']);
    }

    protected function defineCompanyServerLoginRoute() : void
    {
        Route::post('company/login', [CompanyAuthServerController::class , 'login']);
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

        $this->attachGlobalMiddlewares($routeRegistrar);
        
        $this->exceptServerRouteMiddlewares($routeRegistrar);

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
           $this->defineCompanyDefaultAdminsyncingDataServerRoute();
        });
    }

    protected function defineCompanyClientRoutes( string $domain ) : void
    {
        $routeRegistrar = $this->initCompanyRouteRegistrar();

        $this->attachGlobalMiddlewares($routeRegistrar);

        $routeRegistrar->domain($domain); 
        
        $routeRegistrar->group(function()
        {
           $this->defineCompanyClientLoginRoute();
           $this->defineCompanyClientRegisteringRoute();
           $this->defineCompanyClientForgetIdRoute(); 
           $this->defineCompanyClientCheckStatusRoute();
           $this->defineCheckSubdomainClientRoute();
           $this->defineCheckCrNoClientRoute();
        });
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
