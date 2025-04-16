<?php

namespace PixelApp\Interfaces\TenancyInterfaces;

use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;

interface CanSyncData
{   
    public function getTenancyDataSyncingEvent() : ?TenancyDataSyncingEvent;

}