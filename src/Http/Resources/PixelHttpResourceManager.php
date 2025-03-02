<?php

namespace PixelApp\Http\Resources;
 
class PixelHttpResourceManager
{ 
    protected static array $resourceAlternatives = [];

    public static function setResourceAlternative(string $baseResourceClass , string $alternaticveResourceClass) : void
    {
        if($baseResourceClass == $alternaticveResourceClass 
        ||
        is_subclass_of($alternaticveResourceClass , $baseResourceClass)
        )
        {
            static::$resourceAlternatives[$baseResourceClass] = $alternaticveResourceClass;
        }

        dd("The alternative $alternaticveResourceClass Resource class must be a child type class of $baseResourceClass !" );
    }

    public static function getResourceForResourceBaseType(string $baseResourceClass ) : string
    {
        return static::$resourceAlternatives[$baseResourceClass] ?? $baseResourceClass;
    }

}