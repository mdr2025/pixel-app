<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppGetRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class CheckingCrNoValidityRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{   
    protected string $crNo;

    public function __construct(string $crNo)
    {
        $this->crNo = $crNo;    
    }

    protected function getUriParameters() : array
    {
        return ["cr" => $this->crNo];
    }
    protected function getUri() : string
    {
        return "api/check-cr-no/{cr}";
    }
    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        dd($this->getUri());
        return (new PixelAppGetRouteIdentifier($this->getUri() , [] , $this->getUriParameters()));
    }
}