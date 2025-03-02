<?php

namespace PixelApp\Models\Traits;

trait AlternativeModelMethods
{
    protected static array $modelAlternatives = [];

    public static function setModelAlternative(string $baseModelClass , string $alternaticveModelClass) : void
    {
        if($baseModelClass == $alternaticveModelClass 
        ||
        is_subclass_of($alternaticveModelClass , $baseModelClass)
        )
        {
            static::$modelAlternatives[$baseModelClass] = $alternaticveModelClass;
        }

        dd("The alternative $alternaticveModelClass Model class must be a child type class of $baseModelClass !" );
    }

    public static function getModelForModelBaseType(string $baseModelClass ) : string
    {
        return static::$modelAlternatives[$baseModelClass] ?? $baseModelClass;
    }

}