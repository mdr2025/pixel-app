<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\GlobalRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;

class ServerAppAccessTokenFecthingRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{  
    protected string $clientId ;
    protected string $clientSecret;
    protected array $scopes = [];

    public function __construct(string $clientId , string $clientSecret , array $scopes = [])
    {
        $this->clientId = $clientId ;
        $this->clientSecret = $clientSecret;
        $this->scopes = $scopes;
    }

    protected function getUri() : string
    {
        return "oauth/token";
    }

    protected function getData() : array
    {
        return [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => join(' ' , $this->scopes),
        ];
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return new PixelAppPostRouteIdentifier($this->getUri() , $this->getData());
    }
}