<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;

abstract class PixelAppClient
{
    protected static array $instances = [];
    protected string $appRootApi ;

    abstract protected function getAppRootApi() : string;

    protected function __construct()
    {
        $this->setAppRootApi();
    }

    public static function Singleton() : self
    {
        if(!array_key_exists(static::class , static::$instances))
        {
            static::$instances[static::class] = new static();
        }

        return static::$instances[static::class];
    }

    protected function setAppRootApi() : void
    {
        $this->appRootApi = $this->getAppRootApi();
    }

    abstract public function requestOnRoute(PixelAppRouteIdentifier $routeIdentifier) : JsonResponse;

}