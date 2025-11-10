<?php

namespace PixelApp\Config;

use Illuminate\Support\Facades\File;
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
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\DepartmentsConfigFileIdentifier;
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
            SystemResettingExcludedSeedingTablesFileIdentifier::class,
            DepartmentsConfigFileIdentifier::class

        ];
    }
 
    /**
     * For normal app : doesn't return TenanctConfigFileIdentifier 
     */
    public static function getPixelRequiredPackagesMergableConfigFileIdentifierClasses() : array
    {
        return [
                    AuthorizationManagementConfigFileIdentifier::class,
                    ExcelConfigFileIdentifier::class,
                    PermissionConfigFileIdentifier::class,
                    QueryBuilderConfigFileIdentifier::class
                ];
    }

    public static function getPixelRequiredPackagesPublishableConfigFileIdentifierClasses() : array
    {
        return static::getPixelRequiredPackagesMergableConfigFileIdentifierClasses();
    }

    public static function getPixelRequiredPackagesReplacableConfigFileIdentifierClasses() : array
    {
        return [
            PassportConfigFileIdentifier::class
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

    public static function getReplacableFileConfigIdentifierClasses()  : array
    {
        return array_merge(
                    static::getPackageBaseConfigFileIdentifierClasses(),
                    static::getPixelAppLaravelConfigFileIdentifierClasses(),
                    static::getPixelRequiredPackagesReplacableConfigFileIdentifierClasses()
               );
    }
 
    public static function getMergableConfigFileIdentifiers() : array
    {
        return static::getPixelRequiredPackagesMergableConfigFileIdentifierClasses();
    }
    
    public static function getPublishableConfigFileIdentifiers() : array
    {
        return static::getPixelRequiredPackagesMergableConfigFileIdentifierClasses();
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
        static::initPixelConfigStubsManager()->installPackageConfigFiles();
    }

    public static function remergeConfigFile(string $fileName , array $newConfigContent) : void
    {
        $currentMergedConfig = static::getMergedConfigFileContent($fileName);

        $merged = array_merge(
                                  $currentMergedConfig,
                                  $newConfigContent
                             );
        
        config()->set($fileName, $merged);
    }

    
    public static function getConfigFileProjectPath(string $fileName) : string
    {
        return config_path($fileName);
    }

    protected static function getJustReplacedConfigFileContent(string $fileFullName) : array
    {
        $path = static::getConfigFileProjectPath($fileFullName);

        if(File::exists($path))
        {
            return static::getConfigFileContentByPath($path);
        }

        return [];
    }

    public static function remergeJustReplacedConfigFile(string $fileFullName) : void
    {
        $newConfigContent = static::getJustReplacedConfigFileContent($fileFullName);
        static::remergeConfigFile($fileFullName , $newConfigContent);
    }

    public static function overrideConfigFileContent(string $fileName , string $configFilePath , array $fileConfigs) : void
    {
        $configFileContent = "<?php return " . var_export($fileConfigs , true) . " ;";

        File::put($configFilePath , $configFileContent);

        static::remergeConfigFile($fileName , $fileConfigs);
    }

    public static function getMergedConfigFileContent(string $configFileName) : array
    {
        return config($configFileName , []);
    }

    public static function getConfigFileContentByPath(string $configFilePath) : array
    {
        $content = require $configFilePath;

        return is_array($content) ? $content : [];
    }

    /**
     * update the main config path of package 
     * (( that is found in package config path before any file replacement into project config path )) 
     */
    public static function setPixelPackageConfigFileKeys(array $keys) : void
    {
        $configFileIdentifier  = static::initPackageBaseConfigFileIdentifier();
        $configFilePath = $configFileIdentifier->getFilePath();

        $configs = require $configFilePath;
        $configs = array_merge($configs , $keys);
        
        static::overrideConfigFileContent($configFilePath , $configs);
    }

}