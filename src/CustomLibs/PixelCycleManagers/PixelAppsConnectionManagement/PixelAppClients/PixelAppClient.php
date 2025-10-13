<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppDeleteRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppGetRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPutRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\GlobalRouteIdentifierFactories\ServerAppAccessTokenFecthingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager\PixelPassportManager;

abstract class PixelAppClient
{
    protected static array $instances = [];
    protected string $appRootApi ;

    abstract public static function getClientName() : string;
   
    abstract public function getServerAppRootApiConfigKeyName() : string;

    abstract protected function getRootApiConfigValueNonSettingException() : Exception;

    public static function Singleton() : self
    {
        if(!array_key_exists(static::class , static::$instances))
        {
            static::$instances[static::class] = new static();
        }

        return static::$instances[static::class];
    }

    
    public static function getServerAppClientCredentialsPassportKeyName() : string
    {
        return "server-app-client-credentials";
    }

    protected function getServerAppAccessTokenCacheKeyName()
    {
        return "server-app-connection-access-token";
    }

    protected function setAppRootApi() : void
    {
        $this->appRootApi = $this->getAppRootApi(); 
    }
 
    // protected function composeRouteUrl(PixelAppRouteIdentifier $routeIdentifier)  :string
    // {
    //     $uri = ltrim($routeIdentifier->getUri() , "/");
    //     return $this->appRootApi . "/" . $uri;
    // }

    protected function getAppRootApi() : string
    {
        $key = $this->getServerAppRootApiConfigKeyName();
        $api = PixelConfigManager::getPixelAppPackageConfigItem($key , null);

        if(!$api)
        {
            throw $this->getRootApiConfigValueNonSettingException(); 
        }

        return $api ; 
    }
    
    protected  function toJsonResponse(Response $response) :  JsonResponse
    {
        return response()->json($response->json() , $response->status());
    }
    
    protected function getAccessTokenFailingExceptionMessage() : string
    {
        return "Failed to fetch a new access token to connect " . $this::getClientName();
    }

    protected function processAccessTokenFetchingResponse(Response $response) : array
    {
        if($response->failed())
        {
            throw new Exception(  $this->getAccessTokenFailingExceptionMessage() . " , " .  $response->status() . " error code return " );
        }

        $jsonResponseData = $this->toJsonResponse($response)->getData(true);
        
        if($accessToken = $jsonResponseData["access_token"] ?? null)
        {
            return [
                        $accessToken , 
                        $jsonResponseData["expires_in"] ?? null
                ];   
        }

        throw new Exception(  $this->getAccessTokenFailingExceptionMessage() );
    }
    
    protected function getServerAppClientId() : string
    {
        $key = $this::getServerAppClientCredentialsPassportKeyName();
        $clientIdKeyName = PixelPassportManager::getClientIdKeyName();
        return PixelPassportManager::getPassportConfigKeyValue($key , [])[$clientIdKeyName] 
        ??
        throw new Exception( $this->getAccessTokenFailingExceptionMessage() . " , No Client id config value is set !" );
    }
 
    protected function getServerAppClientSecret() : string
    {
        $key = $this::getServerAppClientCredentialsPassportKeyName();
        $clientSecretKeyName = PixelPassportManager::getClientSecretKeyName();
        return PixelPassportManager::getPassportConfigKeyValue($key , [])[$clientSecretKeyName] 
        ??
        throw new Exception( $this->getAccessTokenFailingExceptionMessage() . " , No Client secret config value is set !" );
    }

    protected function initServerAppAccessTokenFecthingRouteIdentifierFactory() : ServerAppAccessTokenFecthingRouteIdentifierFactory
    {
        dd($this->getServerAppClientId() );
        return new ServerAppAccessTokenFecthingRouteIdentifierFactory(
                    $this->getServerAppClientId() ,
                    $this->getServerAppClientSecret()
                );
    }

    protected function initServerAppAccessTokenFecthingRouteIdentifier() : PixelAppPostRouteIdentifier
    {
        return $this->initServerAppAccessTokenFecthingRouteIdentifierFactory()->createRouteIdentifier() ;
    }

    protected function fetchNewAccessToken() : string
    {        
        $routeIdentifier = $this->initServerAppAccessTokenFecthingRouteIdentifier() ;
        $response = $this->postRequest($routeIdentifier , false);
        [$token , $ttl] = $this->processAccessTokenFetchingResponse($response);

        $this->cacheAccessToken($token , $ttl);
        
        return $token ;
    }

    protected function cacheAccessToken(string $accessToken , int $ttl) : void
    {
        Cache::put($this->getServerAppAccessTokenCacheKeyName() , $accessToken , $ttl);
    }
    
    protected function getCachedAccessToken() : ?string
    {
        $token = Cache::get($this->getServerAppAccessTokenCacheKeyName());

        return is_string($token) ? $token : null;
    }

    protected function getAccessToken() : string
    {
        return $this->getCachedAccessToken() ?? $this->fetchNewAccessToken();
    }

    protected function attachAccessToken(PendingRequest $request) : void
    {
        $request->withToken($this->getAccessToken());
    }
 
    protected function initPendingRequest(bool $withAccessToken = true) : PendingRequest
    {
        $pendingRequest = Http::baseUrl($this->appRootApi);

        if($withAccessToken)
        {
            $this->attachAccessToken($pendingRequest);
        }

        return $pendingRequest;
    }

    protected function deleteRequest(PixelAppDeleteRouteIdentifier $routeIdentifier , bool $withAccessToken = true) : Response
    { 
        $pendingRequest = $this->initPendingRequest($withAccessToken);

        return $pendingRequest->delete($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    protected function putRequest(PixelAppPutRouteIdentifier $routeIdentifier, bool $withAccessToken = true) : Response
    { 
        $pendingRequest = $this->initPendingRequest($withAccessToken);
        
        return $pendingRequest->asForm()->put($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    protected function getRequest(PixelAppGetRouteIdentifier $routeIdentifier, bool $withAccessToken = true) : Response
    { 
        $pendingRequest = $this->initPendingRequest($withAccessToken);

        return $pendingRequest->get($routeIdentifier->getUri()  , $routeIdentifier->getData());
    }

    protected function postRequest(PixelAppPostRouteIdentifier $routeIdentifier, bool $withAccessToken = true) : Response
    {
        $pendingRequest = $this->initPendingRequest($withAccessToken);

        return $pendingRequest->asForm()->post($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    public function requestOnRoute(PixelAppRouteIdentifier $routeIdentifier, bool $withAccessToken = true) : JsonResponse
    {
        $this->setAppRootApi();

        $response = null ;

        if($routeIdentifier instanceof PixelAppPostRouteIdentifier)
        {
            $response = $this->postRequest($routeIdentifier , $withAccessToken);
        }

        if($routeIdentifier instanceof PixelAppGetRouteIdentifier)
        {
            $response = $this->getRequest($routeIdentifier , $withAccessToken);
        }

        if($routeIdentifier instanceof PixelAppPutRouteIdentifier)
        {
            $response = $this->putRequest($routeIdentifier , $withAccessToken);
        }
 
        if($routeIdentifier instanceof PixelAppDeleteRouteIdentifier)
        {
            $response = $this->deleteRequest($routeIdentifier , $withAccessToken);
        }

        if(!$response)
        {
            throw new Exception("Unsupported http method is used for route identifier with uri : " . $routeIdentifier->getUri());
        }

        return $this->toJsonResponse($response);
    }

}