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

class PixelAdminPanelAppClient extends PixelAppClient
{
    public static function getClientName() : string
    {
        return "admin-panel";
    }

    public function getAppRootApiConfigKeyName() : string
    {
        return "admin-panel-app-root-api";
    }

    protected function getAppRootApi() : string
    {
        $key = $this->getAppRootApiConfigKeyName();
        $api = PixelConfigManager::getPixelAppPackageConfigItem($key , null);
        return $api ?? 
               throw new Exception("No Admin panel root api value is set in config file !");
    }
    
    protected  function toJsonResponse(Response $response) :  JsonResponse
    {
        return response()->json($response->json() , $response->status());
    }

    protected function deleteRequest(PixelAppDeleteRouteIdentifier $routeIdentifier) : Response
    { 
        return Http::delete($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    protected function putRequest(PixelAppPutRouteIdentifier $routeIdentifier) : Response
    { 
        return Http::asForm()->put($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    protected function getRequest(PixelAppGetRouteIdentifier $routeIdentifier) : Response
    { 
        return Http::get($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    protected function postRequest(PixelAppPostRouteIdentifier $routeIdentifier) : Response
    {
        return Http::asForm()->post($routeIdentifier->getUri() , $routeIdentifier->getData());
    }

    public function requestOnRoute(PixelAppRouteIdentifier $routeIdentifier) : JsonResponse
    {
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