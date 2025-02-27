<?php

namespace PixelApp\Models;

use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager; 

class PixelModelManager
{

    public static function getDefaultPixelUserModelClass() : string
    {
        return PixelUser::class;
    }

    public static function getUserModelClass() : string
    {
        $modelClass = PixelConfigManager::getUserModelClass() ;
        $defaultUserModelClass = static::getDefaultPixelUserModelClass();
        if(!$modelClass)
        {
            dd("User model class is not set in pixel-config file");
        }
     
        if(
            $modelClass === $defaultUserModelClass
            ||
            is_subclass_of($modelClass , $defaultUserModelClass )
            )
        {
            return $modelClass;
        }

        dd("User model class must be child type of use PixelApp\Models\UsersModule\PixelUser type");
    }
 
    public static function getBaseTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getBaseTenantCompanyModelClass();
    }

    public static function getTenantCompanyModelClass() : string
    {
        $modelClass = PixelConfigManager::getTenantCompanyModelClass() ;
        $baseTenantClass = static::getBaseTenantCompanyModelClass();
        
        if($modelClass === $baseTenantClass || is_subclass_of($modelClass ,  $baseTenantClass))
        {
            return $modelClass;
        }
        
        dd("Tenant model class must be child type of $baseTenantClass type");
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