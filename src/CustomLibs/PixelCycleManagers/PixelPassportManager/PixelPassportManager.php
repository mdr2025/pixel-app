<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelPassportManager;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppRequiredPackagesConfigFileIdentifiers\PassportConfigFileIdentifier;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;

class PixelPassportManager
{
    protected static bool $foreignKeyDependentDeleting = true;
 
    public static function disableForeignKeyDependentDeleting() : void
    {
        static::$foreignKeyDependentDeleting = false;
    }

    public static function enableForeignKeyDependentDeleting() : void
    {
        static::$foreignKeyDependentDeleting = true;
    }

    public static function getForeignKeyDependentDeletingStatus() : bool
    {
        return static::$foreignKeyDependentDeleting;
    }

    public static function setupPassport(bool $firstTimeSetup = true , Command $outputCommand) : void
    {
        PixelPassportSetupManager::Singleton()->setupPassport($firstTimeSetup , $outputCommand);
    }

    public static function registerPassportObjects() : void
    {
        PixelPassportRegisteringManager::Singleton()->registerPassportObjects();
    }

    public static function bootPassport() : void
    {
        PixelPassportBootingManager::Singleton()->bootPassport();
    }

    public static function initPassportConfigFileIdentifier() : PassportConfigFileIdentifier
    {
        return PassportConfigFileIdentifier::Singleton();
    }

    protected function checkValue(mixed $value) : bool
    {
        return true; // for handling value if needed
    }

    public static function getPassportConfigFileContent(PassportConfigFileIdentifier $configFileIdentifier) : array
    {
        return config($configFileIdentifier->getConfigKeyName());
    }
    
    protected static function getUpdatedPassportContentArray(PassportConfigFileIdentifier $passprtConfigFileIdentifier , string $key , mixed $value) : array
    {
        $config = static::getPassportConfigFileContent($passprtConfigFileIdentifier);
        if(static::checkValue($value))
        {

            $config[$key] = $value;
        }

        return $config;
    }

    protected static function isConfigFileReplaced(string $filePath) : bool
    {
        return File::exists( $filePath );
    }


    public static function getPassportConfigFilePath(PassportConfigFileIdentifier $passprtConfigFileIdentifier) : string
    {
        $projectRelevantPath = $passprtConfigFileIdentifier->getFileProjectRelevantPath();
        $projectFilePath = config_path($projectRelevantPath);

        if(static::isConfigFileReplaced($projectFilePath))
        {
            return $projectFilePath;
        }

        return $passprtConfigFileIdentifier->getFilePath();
    }

    public static function writeToConfig(string $key , mixed $value) : void
    {
        $passprtConfigFileIdentifier = static::initPassportConfigFileIdentifier();
        
        $passportFilePath = static::getPassportConfigFilePath($passprtConfigFileIdentifier);
        $passportConfigFileContent = static::getUpdatedPassportContentArray($passprtConfigFileIdentifier , $key , $value);
        
        PixelConfigManager::overrideConfigFileContent($passportFilePath , $passportConfigFileContent);
    }

    public static function doesHaveTokensInBothSide()
    {
        return PixelTenancyManager::isItMonolithTenancyApp();
    }

    public static function doesItSupportMachineClientCredentialsGrant() : bool
    {
        return PixelTenancyManager::isItTenantApp() && PixelTenancyManager::isItAdminPanelApp();
    }
    
    public static function doesHaveOnlyTenantTokens()
    {
        return PixelTenancyManager::isItTenantApp();
    }

    public static function doesHaveOnlyCentralTokens() : bool
    {
        return PixelTenancyManager::isItNormalApp() || PixelTenancyManager::isItAdminPanelApp();    
    }

}