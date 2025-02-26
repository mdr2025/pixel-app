<?php

namespace PixelApp\CustomLibs\Tenancy;

use Illuminate\Contracts\Foundation\Application;
use PixelApp\Config\PixelConfigManager;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;
use PixelApp\Http\Middleware\TenancyCustomMiddlewares\ActiveTenantCompany;
use PixelApp\Http\Middleware\TenancyCustomMiddlewares\ApprovedTenantCompany;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class PixelTenancyManager
{
    /**
     * this method is useful when we want to check extra conditions regardless of config value
     */
    public static function isItTenancySupportyerApp() : bool
    {
        return !PixelConfigManager::isItNormalApp();
    }

    public static function isItMonolithTenancyApp() : bool
    {
        return PixelConfigManager::isItMonolithTenancyApp();
    }
 
    public static function isItAdminPanelApp() : bool
    {
        return PixelConfigManager::isItAdminPanelApp();
    }

    public static function isItTenantApp() : bool
    {
        return PixelConfigManager::isItTenantApp();
    }

    public static function isTenantQueryable() : bool
    {
        return static::isItMonolithTenancyApp() || static::isItAdminPanelApp();
    }

    public static function mustHandleDomainQueryStringParam() : bool
    {
       return static::isItMonolithTenancyApp() || static::isItTenantApp();
    }

    public static function getCentralDomains() : array
    {
        return PixelConfigManager::getCentralDomains();
    }
    public static function getTenantDefaultMiddlewares() : array
    {
        return [
                    InitializeTenancyByDomainOrSubdomain::class,
                    PreventAccessFromCentralDomains::class,
                    ActiveTenantCompany::class ,
                    ApprovedTenantCompany::class,
                    'api'
               ];
    }
    
    public static function getBaseTenantCompanyModelClass() : string
    {
        return TenantCompany::class;
    }

    public static function getTenantCompanyModelClass() : string
    {
        return PixelModelManager::getTenantCompanyModelClass();
    }
 
    public static function getPixelTenancyClass() : string
    {
        return PixelTenancy::class;
    }
    
    public static function RegisterPixelTenancyOnNeed(Application $app) : void
    {
        if(static::isItTenancySupportyerApp())
        {
            $app->register( static::getPixelTenancyServiceProviderClass() );
        }
    }
      
    protected static function getPixelTenancyServiceProviderClass() : string
    {
        $tenancyServiceProviderClass = PixelConfigManager::getPixelTenancyServiceProviderClass();
        if(is_subclass_of($tenancyServiceProviderClass , TenancyServiceProvider::class))
        {
            return $tenancyServiceProviderClass;
        }

        //exception only for development
        dd("PixelTenancyServiceProviderClass must be a child class of PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider !");
    }

}