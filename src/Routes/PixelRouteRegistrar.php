<?php

namespace PixelApp\Routes;

abstract class PixelRouteRegistrar
{ 
    abstract public function registerRoutes(?callable $callbackOnRouteRegistrar = null) : void;
}