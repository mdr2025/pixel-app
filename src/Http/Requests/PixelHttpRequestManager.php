<?php

namespace PixelApp\Http\Requests;

use ValidatorLib\CustomFormRequest\BaseFormRequest;

class PixelHttpRequestManager
{ 
    protected static array $requestAlternatives = [];

    public static function setRequestAlternative(string $baseRequestClass , string $alternaticveRequestClass) : void
    {
        static::$requestAlternatives[$baseRequestClass] = $alternaticveRequestClass;
    }

    protected static function getValidAlternativeRequestClass(string $baseRequestClass) : string
    {    
        $alternativeRequestClass = static::$requestAlternatives[$baseRequestClass];

        if( is_subclass_of($alternativeRequestClass , $baseRequestClass) )
        {
            return $alternativeRequestClass;
        }
        
        dd("The alternative Request Form class $alternativeRequestClass must be a child type class of $baseRequestClass !" );    
    }

    protected static function getValidBaseRequestClass(string $baseRequestClass) : string
    {
        if( is_subclass_of($baseRequestClass , BaseFormRequest::class))
        {
            return $baseRequestClass;
        }

        dd($baseRequestClass . " base request class must be a child type of ValidatorLib " . BaseFormRequest::class);
    }

    public static function getRequestForRequestBaseType(string $baseRequestClass ) : string
    {
        if( !array_key_exists($baseRequestClass , static::$requestAlternatives) )
        {
            return static::getValidBaseRequestClass($baseRequestClass);
        }
        
        return static::getValidAlternativeRequestClass($baseRequestClass );
    }

}