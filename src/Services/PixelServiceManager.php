<?php

namespace PixelApp\Services;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;

class PixelServiceManager
{ 
    protected static array $serviceAlternatives = [];

    public static function setServiceAlternative(string $baseServiceClass , string $alternaticveServiceClass) : void
    {
        if($baseServiceClass == $alternaticveServiceClass 
        ||
        is_subclass_of($alternaticveServiceClass , $baseServiceClass)
        )
        {
            static::$serviceAlternatives[$baseServiceClass] = $alternaticveServiceClass;
        }

        dd("The alternative $alternaticveServiceClass service class must be a child type class of $baseServiceClass !" );
    }

    public static function getServiceForServiceBaseType(string $baseServiceClass ) : string
    {
        return static::$serviceAlternatives[$baseServiceClass] ?? $baseServiceClass;
    }

}