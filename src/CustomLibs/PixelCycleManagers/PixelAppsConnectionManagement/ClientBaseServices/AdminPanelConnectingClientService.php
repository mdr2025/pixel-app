<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices;
 
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\Helpers\ResponseHelpers;
use PixelApp\Services\Interfaces\PixelClientService;


abstract class AdminPanelConnectingClientService implements PixelClientService
{
    
    abstract protected function getRouteIdentifierFactory() : PixelAppRouteIdentifierFactory;

    protected function initPixelAppsConnectionManager() : PixelAppsConnectionManager
    {
        return PixelAppsConnectionManager::Singleton();
    }

    protected function getAdminPanelClientName() : string
    {
        return PixelAdminPanelAppClient::getClientName();
    }
    protected function connectOnAdminPanel() : PixelAppClient
    {
        return $this->initPixelAppsConnectionManager()->connectOn( $this->getAdminPanelClientName());
    }

    protected function makeAdminPanelRouteIdentifier() : PixelAppRouteIdentifier
    {
        return $this->getRouteIdentifierFactory()->createRouteIdentifier();
    }

    public function getResponse(): JsonResponse
    {
        $routeIdentifier = $this->makeAdminPanelRouteIdentifier();
        return $this->connectOnAdminPanel()->requestOnRoute($routeIdentifier);
    }
    
    public function getResponseArray(JsonResponse $response) : array
    {
        return ResponseHelpers::getResponseData($response , true);
    }
 
}
