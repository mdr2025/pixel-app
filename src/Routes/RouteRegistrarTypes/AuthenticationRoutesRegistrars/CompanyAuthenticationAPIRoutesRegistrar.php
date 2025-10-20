<?php

namespace PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars;

use Illuminate\Support\Facades\Route;
use PixelApp\Routes\PixelRouteRegistrar;
use Illuminate\Routing\RouteRegistrar;
use PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers\CompanyAuthClientController;
use PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers\CompanyAuthServerController;
use PixelApp\Routes\PixelRouteBootingManager;
use PixelApp\Routes\PixelRouteManager;

class CompanyAuthenticationAPIRoutesRegistrar extends PixelRouteRegistrar 
{ 
    public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        if( PixelRouteBootingManager::isBootingForMonolithTenancyApp()  )
        {
            $this->defineMonolithTenancyAppRoutes(); 

        }elseif( PixelRouteBootingManager::isBootingForTenantApp()  )
        {
            $this->defineTenantAppRoutes();

        }elseif(PixelRouteBootingManager::isBootingForAdminPanelApp())
        {
            $this->defineAdminPanelAppRoutes(); 
        } 
    } 

    public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void
    {
        $arrayToAppend["pixel-app-package-route-registrars"]["company-auth"] = static::class;
    }

    public function isFuncAvailableToDefine() : bool
    {
        return $this->initPixelRoutesInstallingManager()
                    ->isInstallingForTenancySupporterApp();
    }

    protected function defineCheckCrNoServerRoute(bool $remoteServer = true) : void
    {
        $route = Route::get('check-cr-no/{cr}', [CompanyAuthServerController::class , 'checkCrNo']);

        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }
    
    protected function defineCheckCrNoClientRoute() : void
    {
        Route::get('check-cr-no/{cr}', [CompanyAuthClientController::class , 'checkCrNo']);
    }

    protected function defineCheckSubdomainServerRoute(bool $remoteServer = true) : void
    {
        $route = Route::get('check-subdomain/{domain}', [CompanyAuthServerController::class , 'checkSubDomain'] );

        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }

    protected function defineCheckSubdomainClientRoute() : void
    {
        Route::get('check-subdomain/{domain}', [CompanyAuthClientController::class , 'checkSubDomain'] );
    }

    protected function defineCompanyClientCheckStatusRoute() : void
    {
        Route::post('company/check/status', [CompanyAuthClientController::class , 'checkStatus']);
    }

    protected function defineCompanyServerCheckStatusRoute(bool $remoteServer = true) : void
    {
        $route = Route::post('company/check/status', [CompanyAuthServerController::class , 'checkStatus']);

        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }
  
    /**
     * @todo
     * check why it doesn't defnied too
     */
    protected function defineCompanyDefaultAdminUpdatingServerRoute() : void
    {
        $route = Route::post('company/update-default-admin-info', [CompanyAuthServerController::class , 'updateDefaultAdminInfo']);
        $this->attchClientGrantMiddleware($route);
    }
  
    protected function defineCompanyDefaultAdminsyncingDataServerRoute() : void
    {
        $route = Route::post('company/sync-default-admin-data', [CompanyAuthServerController::class , 'syncDefaultAdminData']);
        $this->attchClientGrantMiddleware($route);
    }

    protected function defineCompanyServerEmailVerificationRoute() : void
    {
        Route::post('company/verify-email', [CompanyAuthServerController::class , 'verifyDefaultAdminEmail']);
    }
  
    
    protected function defineCompanyClientForgetIdRoute() : void
    {
        Route::post('company/forget-id', [CompanyAuthClientController::class , 'forgetId']);
    }

    protected function defineCompanyServerForgetIdRoute(bool $remoteServer = true) : void
    {
        $route = Route::post('company/forget-id', [CompanyAuthServerController::class , 'forgetId']);

        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }
 
    protected function defineCompanyClientLoginRoute() : void
    {
        Route::post('company/login', [CompanyAuthClientController::class , 'login']);
    }

    protected function defineCompanyServerLoginRoute(bool $remoteServer = true) : void
    {
        $route = Route::post('company/login', [CompanyAuthServerController::class , 'login']);

        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }

    protected function defineCompanyClientRegisteringRoute() : void
    {
        Route::post('company/register', [CompanyAuthClientController::class , 'register']) ;
    }

    protected function defineCompanyServerRegisteringRoute(bool $remoteServer = true) : void
    {
        $route = Route::post('company/register', [CompanyAuthServerController::class , 'register']) ;
        
        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }

    protected function defineApprovedCompanyIDSFetchingRoute( bool $remoteServer = true) : void
    {
        $route = Route::post('company/fecth-approved-company-ids', [CompanyAuthServerController::class , 'fetchApprovedCompanyIDS']) ;
    
        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }

    protected function definefetchCompanyRoute(bool $remoteServer = true) : void
    {
        $route = Route::post('company/fecth-company', [CompanyAuthServerController::class , 'fetchCompany']) ;
        
        if($remoteServer)
        {
            $this->attchClientGrantMiddleware($route);
        }
    }

    protected function defineCompanyServerRoutes(?string $domain = null , bool $remoteServer = true) : void
    {
        $routeRegistrar = $this->initCompanyRouteRegistrar();

        $this->attachServerRouteMiddlewares($routeRegistrar);

        if($domain)
        {
            $routeRegistrar->domain($domain);
        }
        
        $routeRegistrar->group(function() use ($remoteServer)
        {
           $this->defineCompanyServerLoginRoute($remoteServer);
           $this->defineCompanyServerRegisteringRoute($remoteServer);
           $this->defineCompanyServerForgetIdRoute($remoteServer);
           $this->defineCompanyServerEmailVerificationRoute($remoteServer);
           $this->defineCompanyServerCheckStatusRoute($remoteServer);
           $this->defineCheckSubdomainServerRoute($remoteServer);
           $this->defineCheckCrNoServerRoute($remoteServer);
           
           $this->definefetchCompanyRoute($remoteServer);
           $this->defineApprovedCompanyIDSFetchingRoute($remoteServer);
           $this->defineCompanyDefaultAdminsyncingDataServerRoute($remoteServer);
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
            $this->defineCompanyServerRoutes($domain , false);
        }
    }
    
     
}
