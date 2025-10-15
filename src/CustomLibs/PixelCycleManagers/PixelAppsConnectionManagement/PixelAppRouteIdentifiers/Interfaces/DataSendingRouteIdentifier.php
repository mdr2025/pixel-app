<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\Interfaces;


interface DataSendingRouteIdentifier
{
    public function sendAsMultipart() : self;

    public function shouldBeSentAsMultipart() : bool;
}