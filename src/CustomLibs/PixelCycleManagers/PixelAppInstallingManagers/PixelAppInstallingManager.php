<?php
namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppInstallingManagers;

use Illuminate\Support\Facades\Artisan;
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Config\ConfigEnums\PixelAppTypeEnum;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppConfigFileIdentifier;
use PixelApp\Config\ConfigValueManager;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Helpers\PixelGlobalHelpers;
use PixelApp\Http\Middleware\PixelMiddlewareManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Database\PixelDatabaseManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Routes\PixelRouteManager;
use PixelApp\ServiceProviders\LaravelServiceProviderStubsManager;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;

class PixelAppInstallingManager
{
    protected static PixelAppSystemRequirementsCard $requirementCard;
    protected static string $appType ;

    public static function install(PixelAppSystemRequirementsCard $requirementCard)
    {
        static::usePixelAppSystemRequirementsCard($requirementCard);

        //configuring pixel app package config file before replacing it into config project path
        static::configureSystemType();
        static::definePixelAppFunctinallities();

        //replacing config files and merging their data to be readable by config() helper function
        static::installPackageConfigFiles();

        //replacing the other stubs
        static::installLaravelServiceProviders();
        static::installPackageMiddlewareStubs();
        static::installPackageModels();
        static::installAppDatabaseFiles();

        //handling pixel-dompdf package needed font files
        static::handleDefaultFonts();
    }
    
    protected static function usePixelAppSystemRequirementsCard(PixelAppSystemRequirementsCard $requirementCard) : void
    {
        static::$requirementCard = $requirementCard;
    }
 
    protected static function changePixelAppConfigValue(string $key , mixed $value) : void
    {
        $changes = [$key => $value];
        PixelConfigManager::setPixelPackageConfigFileKeys($changes);
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

    protected static function configureSystemType() : void
    {
        $systemType = static::$requirementCard->getSystemType();

        if(! in_array($systemType , static::getValidAppTypes()))
        {
            dd("The selected app type is not valid !");
        }

        static::changePixelAppConfigValue(
                                            PixelConfigManager::getPixelAppTypeConfigKeyName() ,
                                            $systemType
                                         );
    }
    
    
    protected static function installPackageConfigFiles() : void
    {
        PixelConfigManager::installPackageConfigFiles();
    }

    protected static function definePixelAppFunctinallities() : void
    { 
        PixelRouteManager::installPackageRoutes(static::$requirementCard);
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

    protected static function installAppDatabaseFiles() : void
    {
        PixelDatabaseManager::installAppDatabaseFiles();
    }

    protected static function handleDefaultFonts() : void
    {
        Artisan::call("pixel-app:handle-default-fonts");
    }

}