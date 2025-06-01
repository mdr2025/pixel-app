<?php

namespace PixelApp\CustomLibs\Tenancy;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use PixelApp\Config\PixelConfigManager;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;
use PixelApp\Http\Middleware\TenancyCustomMiddlewares\ActiveTenantCompany;
use PixelApp\Http\Middleware\TenancyCustomMiddlewares\ApprovedTenantCompany;
use PixelApp\Interfaces\TenancyInterfaces\CanSyncData;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\Interfaces\TrustedAttributesHandlerModel;
use PixelApp\Models\PixelModelManager;
use PixelApp\Routes\PixelRouteManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\ApprovedTenantCompaniesFetchingService;
use PixelApp\Services\PixelServiceManager;
use Stancl\Tenancy\Features\TenantConfig;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class PixelTenancyManager
{
    /**
     * this method is useful when we want to check extra conditions regardless of config value
     */
    public static function isItTenancySupporterApp() : bool
    {
        return !PixelConfigManager::isItNormalApp();
    }

    public static function isItNormalApp() : bool
    {
        return PixelConfigManager::isItNormalApp();
    }

    public static function DoesItNeedTenantRoutes() :bool
    {
        return PixelRouteManager::DoesItNeedTenantRoutes();
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

    public static function handleTenancySyncingData(Model $model) : void
    {
        if(
            static::isItTenancySupporterApp() 
            &&
            $model instanceof CanSyncData 
           )
        {
            $model->getTenancyDataSyncingEvent()?->fireEvent();
        }
    }

    public static function isTenantQueryable() : bool
    {
        return static::isItMonolithTenancyApp() || static::isItAdminPanelApp();
    }

    public static function initTenantCompanyNewModel() : TenantCompany
    {
        $modelClass = static::getTenantCompanyModelClass();
        return new $modelClass;
    }

    public static function getRunableTenant(int $id) : TenantCompany
    {
        $model = static::initTenantCompanyNewModel();

        if($model instanceof TrustedAttributesHandlerModel)
        {
            $model->handleModelAttrs([ $id ]);
        }else
        {
            $model->{$model->getKeyName()} = $id;
        }

        return $model;
    }

    
    protected static function initApprovedTenantCompaniesFetchingService() :ApprovedTenantCompaniesFetchingService
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(ApprovedTenantCompaniesFetchingService::class);
        return new $service;
    }

    public static function fetchTenantsByAdminPanel() : Collection
    {
        return static::initApprovedTenantCompaniesFetchingService()->fetchApprovedTenantCompanies();
    }

    public static function fetchTenantsFromCentralSide() : Collection
    {
        return static::getTenantCompanyModelClass()::all(); // to check collection type later (lazy or cursor)
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
        if(static::isItTenancySupporterApp())
        {
            $app->register( static::getPixelTenancyServiceProviderClass() );
        }
    }
      
    protected static function getPixelTenancyServiceProviderClass() : string
    {
        $tenancyServiceProviderClass = PixelConfigManager::getPixelTenancyServiceProviderClass();
        if(
            $tenancyServiceProviderClass === TenancyServiceProvider::class
            ||
            is_subclass_of($tenancyServiceProviderClass , TenancyServiceProvider::class)
          )
        {
            return $tenancyServiceProviderClass;
        }

        //exception only for development
        dd("PixelTenancyServiceProviderClass must be a child class of PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider !");
    }

}