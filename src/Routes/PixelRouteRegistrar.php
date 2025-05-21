<?php

namespace PixelApp\Routes;


use Illuminate\Routing\RouteRegistrar;
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;

abstract class PixelRouteRegistrar
{ 

    abstract public function bootRoutes(?callable $callbackOnRouteRegistrar = null) : void;
    abstract public function appendRouteRegistrarConfigKey(array &$arrayToAppend) : void;
    /**
     * because it is applied on the client side where the requests come from
     * and the server fails down on applying the middleware on the two sides (server + client)
     * especially  when the route names and paths are the same 
     */
    protected function exceptServerRouteMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->withoutMiddleware( 'throttle:api' );
    }

    public function isFuncAvailableToDefine(PixelAppSystemRequirementsCard $requirementsCard) : bool
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

    protected function attachGlobalMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->middleware( $this->getGlobalMiddlewares() );
    }

    protected function attachTenantMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $tenantMiddlewares = array_merge($this->getGlobalMiddlewares() , $this->getTenancyMiddlewares());
        $routeRegistrar->middleware($tenantMiddlewares);
    }

    
}