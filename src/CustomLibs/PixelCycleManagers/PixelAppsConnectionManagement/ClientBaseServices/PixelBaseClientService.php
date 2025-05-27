<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices;
 
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\Helpers\ResponseHelpers;

abstract class PixelBaseClientService
{
    abstract protected function getClientName() : string;

    abstract protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory;

    protected function initPixelAppsConnectionManager() : PixelAppsConnectionManager
    {
        return PixelAppsConnectionManager::Singleton();
    }
    
    protected function connectOnClient() : PixelAppClient
    {
        return $this->initPixelAppsConnectionManager()->connectOn( $this->getClientName());
    }

    protected function createRouteIdentifier() : PixelAppRouteIdentifier
    {
        return $this->getRouteIdentifierFactory()->createRouteIdentifier();
    }

    public function getResponse(): JsonResponse
    {
        $routeIdentifier = $this->createRouteIdentifier();
        return $this->connectOnClient()->requestOnRoute($routeIdentifier);
    }
     
    public function getResponseArray(JsonResponse $response) : array
    {
        return ResponseHelpers::getResponseData($response , true);
    }
}