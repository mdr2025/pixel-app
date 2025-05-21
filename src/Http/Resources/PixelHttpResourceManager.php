<?php

namespace PixelApp\Http\Resources;
 
use Illuminate\Http\Resources\Json\JsonResource;

class PixelHttpResourceManager
{ 
    protected static array $resourceAlternatives = [];

    public static function setResourceAlternative(string $baseResourceClass , string $alternaticveResourceClass) : void
    {
        static::$resourceAlternatives[$baseResourceClass] = $alternaticveResourceClass;
    }

    protected static function getValidAlternativeResourceClass(string $baseResourceClass) : string
    {    
        $alternativeResourceClass = static::$resourceAlternatives[$baseResourceClass];

        if( is_subclass_of($alternativeResourceClass , $baseResourceClass) )
        {
            return $alternativeResourceClass;
        }
        
        dd("The alternative Resource class $alternativeResourceClass must be a child type class of $baseResourceClass !" );    
    }

    protected static function getValidBaseResourceClass(string $baseResourceClass) : string
    {
        if( is_subclass_of($baseResourceClass , JsonResource::class))
        {
            return $baseResourceClass;
        }

        dd($baseResourceClass . " base resource class must be a child type of  " . JsonResource::class);
    }

    public static function getResourceForResourceBaseType(string $baseResourceClass ) : string
    {
        if( !array_key_exists($baseResourceClass , static::$resourceAlternatives) )
        {
            return static::getValidBaseResourceClass($baseResourceClass);
        }
        
        return static::getValidAlternativeResourceClass($baseResourceClass );
    }

}