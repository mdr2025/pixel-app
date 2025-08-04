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
        $arrayToAppend["company-auth"] = static::class;
    }

    public function isFuncAvailableToDefine() : bool
    {
        return $this->initPixelRoutesInstallingManager()
                    ->isInstallingForTenancySupporterApp();
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

        $this->attachGlobalMiddlewares($routeRegistrar);
        
        $this->exceptServerRouteMiddlewares($routeRegistrar);

        if($domain)
        {
            $routeRegistrar->domain($domain);
        }
        
        $routeRegistrar->group(function() use ($remoteServer)
        {
           $this->defineCompanyServerLoginRoute();
           $this->defineCompanyServerRegisteringRoute();
           $this->defineCompanyServerForgetIdRoute();
           $this->defineCompanyServerEmailVerificationRoute();
           $this->defineCompanyServerCheckStatusRoute();
           $this->defineCheckSubdomainServerRoute();
           $this->defineCheckCrNoServerRoute();
           $this->definefetchCompanyRoute();
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

           /**
            * @todo
            * these routes maybe are not required for tenant app ... it is requried for central app or admin panel .... make sure later
            */           
        //    $this->defineCompanyClientCheckStatusRoute();
        //    $this->defineCheckSubdomainClientRoute();
        //    $this->defineCheckCrNoClientRoute();
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
