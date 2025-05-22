<?php

namespace PixelApp\Models\Traits;

use PixelApp\Models\PixelBaseModel;

trait AlternativeModelMethods
{
    protected static array $modelAlternatives = [];

    public static function setModelAlternative(string $baseModelClass , string $alternaticveModelClass) : void
    {
        static::$modelAlternatives[$baseModelClass] = $alternaticveModelClass;
    }

    protected static function getValidAlternativeModelClass(string $baseModelClass) : string
    {    
        $alternativeModelClass = static::$modelAlternatives[$baseModelClass];

        if( is_subclass_of($alternativeModelClass , $baseModelClass) )
        {
            return $alternativeModelClass;
        }
        
        dd("The alternative model class $alternativeModelClass must be a child type class of $baseModelClass !" );    
    }

    protected static function getValidBaseModelClass(string $baseModelClass) : string
    {
        if( is_subclass_of($baseModelClass , PixelBaseModel::class))
        {
            return $baseModelClass;
        }

        dd($baseModelClass . " base model class must be a child type of  " . PixelBaseModel::class);
    }

    public static function getModelForModelBaseType(string $baseModelClass ) : string
    {
        if( !array_key_exists($baseModelClass , static::$modelAlternatives) )
        {
            return static::getValidBaseModelClass($baseModelClass);
        }
        
        return static::getValidAlternativeModelClass($baseModelClass );
    }
 
}