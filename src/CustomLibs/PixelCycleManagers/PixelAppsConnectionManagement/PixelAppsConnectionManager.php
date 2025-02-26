<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement;

use Exception;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAppClient;

class PixelAppsConnectionManager
{
    protected static ?self $instance = null;
    protected array $pixelAppClients = [];

    private function __construct(){}

    public static function Singleton() : PixelAppsConnectionManager
    {
        if(!static::$instance)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function registerPixelAppClient(string $pixelAppClient , ?string $pixelAppClientName = null)
    {
        if(! is_subclass_of( $pixelAppClient ,  PixelAppClient::class ) )
        {
            throw new Exception("Failed to register a PixelAppClient class , " . $pixelAppClient . " is not a PixelAppClient typed class");
        }

        $this->pixelAppClients[$pixelAppClientName] = $pixelAppClient;
    }

    protected function initPixelAppClient(string $pixelAppClientClass) : PixelAppClient
    {
        return $pixelAppClientClass::Singleton();
    }

    public function connectOn(string $pixelAppClientName) : PixelAppClient
    {
        if(!array_key_exists($pixelAppClientName , $this->pixelAppClients) )
        {
            throw new Exception("No PixelAppCliuent registered with name $pixelAppClientName !");
        } 

        $pixelAppClientClass = $this->pixelAppClients[$pixelAppClientName];
        return $this->initPixelAppClient( $pixelAppClientClass );
    }
}