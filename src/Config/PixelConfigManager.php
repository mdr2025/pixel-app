<?php

namespace PixelApp\Config;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\MockObject\Builder\Stub;
use PixelApp\Config\ConfigFileIdentifiers\ConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\AppConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\AuthConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\CorsConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\DatabaseConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\FilesystemsConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\QueueConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers\AuthorizationManagementConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers\ExcelConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers\PassportConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers\PermissionConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers\QueryBuilderConfigFileIdentifier; 
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\IOEncryptingHandlerFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelOptionalPackagesConfigFileIdentifires\TenancyConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppACLConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\SystemResettingExcludedSeedingTablesFileIdentifier;
use PixelApp\Config\Traits\ConfigValueGetters; 

class PixelConfigManager
{
    use ConfigValueGetters;

    public static function getPackageBaseConfigFileIdentifierClass() : string
    {
        return PixelAppConfigFileIdentifier::class ;
    }
   
    protected static function initPackageBaseConfigFileIdentifier() : PixelAppConfigFileIdentifier
    {
        $identifierClass = static::getPackageBaseConfigFileIdentifierClass();
        return static::initConfigFileIdentifier($identifierClass);
    }

    public static function getPixelAppACLConfigFileIdentifierClass() : string
    {
        return PixelAppACLConfigFileIdentifier::class;
    }

    public static function getPackageBaseConfigFileIdentifierClasses() : array
    {
        return [  
            static::getPackageBaseConfigFileIdentifierClass(),
            static::getPixelAppACLConfigFileIdentifierClass() ,
            IOEncryptingHandlerFileIdentifier::class,
            SystemResettingExcludedSeedingTablesFileIdentifier::class
        ];
    }
 
    /**
     * For normal app : doesn't return TenanctConfigFileIdentifier 
     */
    public static function getPixelAppRequiredPackagesConfigFileIdentifierClasses() : array
    {
        return [
            AuthorizationManagementConfigFileIdentifier::class,
            ExcelConfigFileIdentifier::class,
            PassportConfigFileIdentifier::class,
            PermissionConfigFileIdentifier::class,
            QueryBuilderConfigFileIdentifier::class
        ];
    }
    public static function getTenancyConfigFileIdentifierClass() : string
    {
        return TenancyConfigFileIdentifier::class;
    }
 
    public static function getPixelAppLaravelConfigFileIdentifierClasses() : array
    {
        return [ 
            AppConfigFileIdentifier::class,
            AuthConfigFileIdentifier::class,
            CorsConfigFileIdentifier::class,
            DatabaseConfigFileIdentifier::class,
            FilesystemsConfigFileIdentifier::class,
            QueueConfigFileIdentifier::class
        ];
    }
 
    public static function getMergableConfigFileIdentifiers() : array
    {
        return array_merge(
            static::getPackageBaseConfigFileIdentifierClasses(),
            static::getPixelAppRequiredPackagesConfigFileIdentifierClasses()
        );
    }
    
    public static function getPublishableConfigFileIdentifiers() : array
    {
        return array_merge(
            static::getPackageBaseConfigFileIdentifierClasses(),
            static::getPixelAppRequiredPackagesConfigFileIdentifierClasses()
        );
    }
   
    protected static function initConfigFileIdentifier(string $identifierClass) : ConfigFileIdentifier
    { 
        return $identifierClass::Singleton();
    }
 
    protected static function initPixelConfigStubsManager() : PixelConfigStubsManager
    {
        return PixelConfigStubsManager::Singleton();
    }

    public static function installPackageConfigFiles() : void
    {
        static::initPixelConfigStubsManager()->replacePackageConfigFiles();
    }

    public static function overrideConfigFileContent(string $configFilePath , array $fileConfigs) : void
    {
        $configFileContent = "<?php return " . var_export($fileConfigs , true) . " ;";

        File::put($configFilePath , $configFileContent);
    }

    public static function setPixelPackageConfigFileKeys(array $keys) : void
    {
        $configFileIdentifier  = static::initPackageBaseConfigFileIdentifier();
        $configFilePath= $configFileIdentifier->getFilePath();

        $configs = require $configFilePath;
        $configs = array_merge($configs , $keys);
        
        static::overrideConfigFileContent($configFilePath , $configs);
    }

}