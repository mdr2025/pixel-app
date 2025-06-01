<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager;

use Laravel\Passport\Passport; 

class PixelPassportRegisteringManager
{
    protected static $instance = null;

    protected function __construct(){}

    public static function Singleton() : self
    {
        if(!  static::$instance )
        {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    public function registerPassportObjects() : void
    { 
        Passport::ignoreMigrations();
    }
}