<?php

namespace PixelApp\Routes;

use Illuminate\Routing\Route;
use Illuminate\Routing\RouteRegistrar;

abstract class PixelRouteRegistrar
{ 

    abstract public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void;
    abstract public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void;
    
    protected function getServerRouteExcludedMiddlewares() : array
    {
        /**
         * excluded because it is applied on the client route that call the server route 
         * and it protected by client middleware ... so we are sre that we are calling it by our other machine (application)
         */
        return [ 'throttle:api'];
    }
    /**
     * because it is applied on the client side where the requests come from
     * and the server fails down on applying the middleware on the two sides (server + client)
     * especially  when the route names and paths are the same 
     */
    protected function exceptServerRouteMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $excluded = $this->getServerRouteExcludedMiddlewares();

        $routeRegistrar->withoutMiddleware( $excluded );
    }

    protected function attchClientGrantMiddleware(Route $route) : void
    {
        $route->middleware("client");
    }

    protected function initPixelRoutesInstallingManager()  :PixelRoutesInstallingManager
    {
        return PixelRoutesInstallingManager::Singlton();
    }

    public function isFuncAvailableToDefine() : bool
    {
        return true;
    }

    protected function getGlobalMiddlewares() : array
    {
        return ['api' ];
    }

    protected function getTenancyMiddlewares() : array
    {
        return PixelRouteManager::getTenancyMiddlewares();
    }

    protected function getServerRouteGlobalMiddlewares() : array
    {
        $globaMiddlewares = $this->getGlobalMiddlewares();
        $globaMiddlewares[] = "client";   

        return $globaMiddlewares;
    }

    protected function attachServerRouteMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $globalMiddlewares = $this->getServerRouteGlobalMiddlewares();

        $routeRegistrar->middleware( $globalMiddlewares );

        $this->exceptServerRouteMiddlewares($routeRegistrar);
    }

    protected function attachGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( $this->getGlobalMiddlewares() );
    }

    protected function getTenantRouteMiddlewares() : array
    {
        return array_merge($this->getGlobalMiddlewares() , $this->getTenancyMiddlewares());
    }

    protected function attachTenantMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $tenantMiddlewares = $this->getTenantRouteMiddlewares(); 
        $routeRegistrar->middleware($tenantMiddlewares);
    }

    
}