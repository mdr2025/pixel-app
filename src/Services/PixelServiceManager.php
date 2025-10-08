<?php

namespace PixelApp\Services;

 
class PixelServiceManager
{ 
    protected static array $serviceAlternatives = [];

    public static function setServiceAlternative(string $baseServiceClass , string $alternaticveServiceClass) : void
    {
        if(
            $baseServiceClass !== $alternaticveServiceClass 
            &&
            !is_subclass_of($alternaticveServiceClass , $baseServiceClass)
        )
        {
            dd("The alternative $alternaticveServiceClass service class must be a child type class of $baseServiceClass !" );
        }

        static::$serviceAlternatives[$baseServiceClass] = $alternaticveServiceClass;
    }

    public static function getServiceForServiceBaseType(string $baseServiceClass ) : string
    {
        return static::$serviceAlternatives[$baseServiceClass] ?? $baseServiceClass;
    }

}