<?php

namespace PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEventFactories;

use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;

abstract class TenancyDataSyncingEventFactory
{
    abstract public function createTenancyDataSyncingEvent() : ?TenancyDataSyncingEvent;
}