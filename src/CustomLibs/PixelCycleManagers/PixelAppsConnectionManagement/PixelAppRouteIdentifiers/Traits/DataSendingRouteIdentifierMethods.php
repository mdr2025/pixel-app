<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\Traits;


trait DataSendingRouteIdentifierMethods
{
    protected bool $sendAsMultipart = false;
    
    public function sendAsMultipart() : self
    {
        $this->sendAsMultipart = true;
        return $this;
    }

    public function shouldBeSentAsMultipart() : bool
    {
        return $this->sendAsMultipart;
    }
}