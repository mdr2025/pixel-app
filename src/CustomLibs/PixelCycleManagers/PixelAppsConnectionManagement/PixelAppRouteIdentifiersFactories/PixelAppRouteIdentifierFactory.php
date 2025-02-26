<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;

abstract class PixelAppRouteIdentifierFactory
{ 
    abstract public function createRouteIdentifier()  :PixelAppRouteIdentifier;
}