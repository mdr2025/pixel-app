<?php

namespace PixelApp\Models;

use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\Traits\AlternativeModelMethods;

class PixelModelManager
{
    use AlternativeModelMethods ;

    public static function getDefaultPixelUserModelClass() : string
    {
        return PixelUser::class;
    }

    protected static function modelClassInstanceOfParentOrFail(string $modelClass , string $parentClass) : void
    {  
        if(
            $modelClass !== $parentClass
            &&
            !is_subclass_of($modelClass , $parentClass )
            )
        {
            //need the error message for development only
            dd("$modelClass model class must be child type of use $parentClass type");
        }    
    }

    public static function getUserModelClass() : string
    {
        $modelClass = PixelConfigManager::getUserModelClass() ;
        $defaultUserModelClass = static::getDefaultPixelUserModelClass();
    
        if(!$modelClass)
        {
            return $defaultUserModelClass;
        } 

        static::modelClassInstanceOfParentOrFail($modelClass , $defaultUserModelClass);
        
        return $modelClass;
        
    }
 
    public static function getBaseTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getBaseTenantCompanyModelClass();
    }

    public static function getTenantCompanyModelClass() : string
    {
        $modelClass = PixelConfigManager::getTenantCompanyModelClass() ;
        $baseTenantClass = static::getBaseTenantCompanyModelClass();
          
        if(!$modelClass)
        {
            return $baseTenantClass;
        }
  
        static::modelClassInstanceOfParentOrFail($modelClass , $baseTenantClass);
        
        return $modelClass; 
    }

    public static function getProjectModelsPath() : string
    {
        return app_path("Models");
    }

    protected static function initPixelModelStubsManager() : PixelModelStubsManager
    {
        return PixelModelStubsManager::Singleton();
    }

    public static function installPackageModels() : void
    {
        static::initPixelModelStubsManager()->replacePixelAppModelStubs();
    }
}