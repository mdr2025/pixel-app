<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppDeleteRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppGetRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPutRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;

abstract class PixelAppClient
{
    protected static array $instances = [];
    protected string $appRootApi ;

    abstract public function getAppRootApiConfigKeyName() : string;

    abstract protected function getRootApiConfigValueNonSettingException() : Exception;

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
 
    protected function composeRouteUrl(PixelAppRouteIdentifier $routeIdentifier)  :string
    {
        $uri = ltrim($routeIdentifier->getUri() , "/");
        return $this->appRootApi . "/" . $uri;
    }

    protected function getAppRootApi() : string
    {
        $key = $this->getAppRootApiConfigKeyName();
        $api = PixelConfigManager::getPixelAppPackageConfigItem($key , null);
        return $api ?? $this->getRootApiConfigValueNonSettingException();  
    }
    
    protected  function toJsonResponse(Response $response) :  JsonResponse
    {
        return response()->json($response->json() , $response->status());
    }

    protected function deleteRequest(PixelAppDeleteRouteIdentifier $routeIdentifier) : Response
    { 
        $url = $this->composeRouteUrl($routeIdentifier);
        return Http::delete($url , $routeIdentifier->getData());
    }

    protected function putRequest(PixelAppPutRouteIdentifier $routeIdentifier) : Response
    { 
        $url = $this->composeRouteUrl($routeIdentifier);
        return Http::asForm()->put($url , $routeIdentifier->getData());
    }

    protected function getRequest(PixelAppGetRouteIdentifier $routeIdentifier) : Response
    { 
        $url = $this->composeRouteUrl($routeIdentifier);
        return Http::get($url , $routeIdentifier->getData());
    }

    protected function postRequest(PixelAppPostRouteIdentifier $routeIdentifier) : Response
    {
        $url = $this->composeRouteUrl($routeIdentifier);
        return Http::asForm()->post($url , $routeIdentifier->getData());
    }

    public function requestOnRoute(PixelAppRouteIdentifier $routeIdentifier) : JsonResponse
    {
        $this->setAppRootApi();

        $response = null ;

        if($routeIdentifier instanceof PixelAppPostRouteIdentifier)
        {
            $response = $this->postRequest($routeIdentifier);
        }

        if($routeIdentifier instanceof PixelAppGetRouteIdentifier)
        {
            $response = $this->getRequest($routeIdentifier);
        }

        if($routeIdentifier instanceof PixelAppPutRouteIdentifier)
        {
            $response = $this->putRequest($routeIdentifier);
        }
 
        if($routeIdentifier instanceof PixelAppDeleteRouteIdentifier)
        {
            $response = $this->deleteRequest($routeIdentifier);
        }

        if(!$response)
        {
            throw new Exception("Unsupported http method is used for route identifier with uri : " . $routeIdentifier->getUri());
        }

        return $this->toJsonResponse($response);
    }

}