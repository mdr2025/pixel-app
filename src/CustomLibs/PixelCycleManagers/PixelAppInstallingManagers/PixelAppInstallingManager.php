<?php
namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers;

use Illuminate\Support\Facades\Artisan;
use PixelApp\Config\ConfigEnums\PixelAppTypeEnum;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppConfigFileIdentifier;
use PixelApp\Config\ConfigValueManager;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Helpers\PixelGlobalHelpers;
use PixelApp\Http\Middleware\PixelMiddlewareManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Routes\PixelRouteManager;
use PixelApp\ServiceProviders\LaravelServiceProviderStubsManager;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;

class PixelAppInstallingManager
{
    protected static string $appType ;

    public static function install(string $appType)
    {
        static::forAppType($appType);
        static::installPackageConfigFiles(); 
        static::installLaravelServiceProviders();
        static::installPackageMiddlewareStubs();
        static::installPackageModels();
        static::handleDefaultFonts();
    }
    
    public static function getDefaultAppType() : string
    {
        return PixelAppTypeEnum::DEFAULT_PIXEL_APP_TYPE;
    }

    public static function getValidAppTypes() : array
    {
        return [
            PixelAppTypeEnum::TENANT_APP_TYPE,
            PixelAppTypeEnum::ADMIN_PANEL_APP_TYPE ,
            PixelAppTypeEnum::MONOLITH_TENANCY_APP_TYPE ,
            PixelAppTypeEnum::NORMAL_APP_TYPE
        ];
    }

    protected static function forAppType(string $appType) : void
    {
        if(! in_array($appType , static::getValidAppTypes()))
        {
            dd("The selected app type is not valid !");
        }

        static::$appType = $appType;
    }
    
    protected static function getConfigKeysBasedOnAppType() : array
    {
        return  [PixelConfigManager::getPixelAppConfigKeyName() => static::$appType];
    }
    
    protected static function installPackageConfigFiles() : void
    {
        PixelConfigManager::setPixelPackageConfigFileKeys(
                                static::getConfigKeysBasedOnAppType()
                            );

        PixelConfigManager::installPackageConfigFiles();
    }

    protected static function installPackageRoutesFiles() : void
    { 
        PixelRouteManager::installPackageRoutesFiles();
    }
 
    protected static function initLaravelServiceProviderStubsManager() : LaravelServiceProviderStubsManager
    {
        return LaravelServiceProviderStubsManager::Singleton();
    }

    protected static function installLaravelServiceProviders() : void
    {
        static::initLaravelServiceProviderStubsManager()->installLaravelProjectServiceProviders(); 
    }
 
    protected static function installPackageMiddlewareStubs() : void
    {
        PixelMiddlewareManager::installPackageMiddlewareStubs();
    }

    protected static function installPackageModels() : void
    {
        PixelModelManager::installPackageModels();
    }

    protected static function handleDefaultFonts() : void
    {
        Artisan::call("php artisan pixel-app:handle-default-fonts");
    }

}