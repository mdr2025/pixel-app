<?php

namespace PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromTenantSideListeners\SeparatedTenantAppListeners;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\SeparatedTenantAppEvents\AdminPanelDBDataSyncingEvent;
use PixelApp\Listeners\TenancyListeners\DataSyncingListeners\TenancyDataSyncingListener;
 

/**
 * When a tenant app's model need to sync its data with central app's model
 */
class AdminPanelDBDataSyncingListener extends TenancyDataSyncingListener
{ 
    protected function processResponse(JsonResponse $response) : void
    {
        return;
    }
    
    protected function getRouteIdentifier(AdminPanelDBDataSyncingEvent $event) : PixelAppRouteIdentifier
    {
        return $event->getPixelAppRouteIdentifierFactory()->createRouteIdentifier();
    }
 
    protected function connectOnAdminPanel() : PixelAdminPanelAppClient
    {
        return PixelAppsConnectionManager::Singleton()->connectOn( PixelAdminPanelAppClient::getClientName() );
    }

    public function handle(AdminPanelDBDataSyncingEvent $event)
    { 
        $response = $this->connectOnAdminPanel()
                         ->requestOnRoute( $this->getRouteIdentifier($event) );
        $this->processResponse($response);
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
