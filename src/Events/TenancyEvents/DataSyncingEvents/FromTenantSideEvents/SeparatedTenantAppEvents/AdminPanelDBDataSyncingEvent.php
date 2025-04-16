<?php

namespace PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\SeparatedTenantAppEvents;

use Exception; 
use Illuminate\Broadcasting\PrivateChannel; use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent; 

/**
 * When a tenant app's model need to sync its data with central app's model
 */
class AdminPanelDBDataSyncingEvent extends TenancyDataSyncingEvent
{
    protected PixelAppRouteIdentifierFactory $routeIdentifierFactory;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct(PixelAppRouteIdentifierFactory $routeIdentifierFactory)
    {
        $this->setPixelAppRouteIdentifierFactory($routeIdentifierFactory);
    }

    protected function setPixelAppRouteIdentifierFactory(PixelAppRouteIdentifierFactory $routeIdentifierFactory) : self
    {
        $this->routeIdentifierFactory = $routeIdentifierFactory;
        return $this;
    }
 
    public function getPixelAppRouteIdentifierFactory(): PixelAppRouteIdentifierFactory
    {
        return $this->routeIdentifierFactory;
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
