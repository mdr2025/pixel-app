<?php

namespace PixelApp\Http\Requests;
 
class PixelHttpRequestManager
{ 
    protected static array $requestAlternatives = [];

    public static function setRequestAlternative(string $baseRequestClass , string $alternaticveRequestClass) : void
    {
        if(
            $baseRequestClass !== $alternaticveRequestClass 
            &&
            !is_subclass_of($alternaticveRequestClass , $baseRequestClass)
        )
        {
            dd("The alternative $alternaticveRequestClass Request class must be a child type class of $baseRequestClass !" );    
        }

        static::$requestAlternatives[$baseRequestClass] = $alternaticveRequestClass;
        
    }

    public static function getRequestForRequestBaseType(string $baseRequestClass ) : string
    {
        return static::$requestAlternatives[$baseRequestClass] ?? $baseRequestClass;
    }

}