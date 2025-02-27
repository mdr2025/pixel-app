<?php

namespace PixelApp\Config\Traits;

use PixelApp\Config\ConfigEnums\PixelAppTypeEnum; 
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppACLConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelOptionalPackagesConfigFileIdentifires\TenancyConfigFileIdentifier;

trait ConfigValueGetters
{ 
    protected static function getTenancyConfigKey() : string
    {
        return TenancyConfigFileIdentifier::Singleton()->getConfigKeyName();
    }

    public static function getCentralDomains(): array
    {
        return config(static::getTenancyConfigKey() . '.central_domains' , []);
    }
    
    public static function getPixelAppPackageACLConfigKeyName() : string
    {
        return PixelAppACLConfigFileIdentifier::Singleton()->getConfigKeyName(); 
    }

    public static function getPixelAppPackageACLConfigs() : array
    {
        return config(static::getPixelAppPackageACLConfigKeyName() , []);
    }
    public static function getPixelAppPackagePermissions() : array
    {
        return  static::getPixelAppPackageACLConfigs()["permissions"] ?? [];
    }

    public static function getPixelAppPackageConfigsKeyName() : string
    {
        return PixelAppConfigFileIdentifier::Singleton()->getConfigKeyName();
    }
    
    public static function getPixelAppPackageConfigs() : array
    {
        return config(static::getPixelAppPackageConfigsKeyName() , []);
    }

    public static function getPixelAppPackageConfigItem(string $key , mixed $default) : mixed
    {
        return static::getPixelAppPackageConfigs()[$key] ?? $default;
    }
    
    public static function getPixelMacroableExtenders() : array
    {
        return static::getPixelAppPackageConfigItem("pixel-macroable-extenders" , [])  ;
    }

    public static function getPixelTenancyServiceProviderClass() : string
    {
        return static::getPixelAppPackageConfigItem("pixel-tenancy-service-provider-class" , "")  ;
    }
    public static function getPixelAppPackageRouteRegistrars() : array
    {
        return  static::getPixelAppPackageConfigItem("pixel-app-package-route-registrars" , []);
    }

    public static function getPixelAppConfigKeyName() : string
    {
        return "pixel-app-type";
    }
    public static function getPixelAppType() : string
    {
        return static::getPixelAppPackageConfigItem( static::getPixelAppConfigKeyName() , PixelAppTypeEnum::DEFAULT_PIXEL_APP_TYPE );
    }

    public static function isItTenantApp() : bool
    {
        return static::getPixelAppType() === PixelAppTypeEnum::TENANT_APP_TYPE;
    }
    
    public static function isItAdminPanelApp() : bool
    {
        return static::getPixelAppType() === PixelAppTypeEnum::ADMIN_PANEL_APP_TYPE;
    }

    public static function isItMonolithTenancyApp() : bool
    {
        return static::getPixelAppType() === PixelAppTypeEnum::MONOLITH_TENANCY_APP_TYPE;
    }

    public static function isItNormalApp() : bool
    {
        return static::getPixelAppType() === PixelAppTypeEnum::NORMAL_APP_TYPE;
    }
     
    public static function getUserModelClass() : ?string
    {
        return static::getPixelAppPackageConfigItem("user-model-class" , null);
    }

    public static function getTenantCompanyModelClass() : ?string
    {
        return static::getPixelAppPackageConfigItem("tenant-company-model-class" , null);
    }
}