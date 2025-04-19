<?php

namespace PixelApp\Routes;


use Illuminate\Routing\RouteRegistrar;

abstract class PixelRouteRegistrar
{ 
     
    protected function exceptServerRouteMiddlewares(RouteRegistrar $routeRegistrar) : void
    {
        $routeRegistrar->withoutMiddleware( 'throttle:api' );
    }

    abstract public function registerRoutes(?callable $callbackOnRouteRegistrar = null) : void;
}