<?php

namespace PixelApp\CustomLibs\Tenancy;


use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyFetchingService as CompanyFetchingClientService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use PixelApp\Config\PixelConfigManager;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
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
    public static function isItTenancySupporterApp() : bool
    {
        return PixelAppBootingManager::isBootingForTenancySupporterApp();
    }

    public static function isItNormalApp() : bool
    {
        return PixelAppBootingManager::isBootingForNormalApp();
    }
    
    public static function isItMonolithTenancyApp() : bool
    {
        return PixelAppBootingManager::isBootingForMonolithTenancyApp();
    }
 
    public static function isItAdminPanelApp() : bool
    {
        return PixelAppBootingManager::isBootingForAdminPanelApp();
    }

    public static function isItTenantApp() : bool
    {
        return PixelAppBootingManager::isBootingForTenantApp();
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

    public static function fetchTenantByAdminPanel() : ?TenantCompany
    {
        return static::initApprovedTenantCompaniesFetchingService()->fetchApprovedTenantCompanies();
    }

    /**
     * getting only approved tenant companies
     */
    public static function fetchApprovedTenantsByAdminPanel() : Collection
    {
        return static::initApprovedTenantCompaniesFetchingService()->fetchApprovedTenantCompanies();
    }

    
    /**
     * getting only approved tenant companies
     */
    public static function fetchApprovedTenantsFromCentralSide() : Collection
    {
        return static::getTenantCompanyModelClass()::isNotPending()->get(); // to check collection type later (lazy or cursor)
    }

    /**
     * gets any tenant company eather if it is approved or not
     */
    public static function fetchTenantForClientSide(string $domain) : ?TenantCompany
    {
        return (new CompanyFetchingClientService($domain))->fetchTenantCompany();
    }

    /**
     * gets any tenant company eather if it is approved or not
     */
    public static function fetchTenantForServerSide(string $domain) : ?TenantCompany
    {
        return (new CompanyFetchingService())->fetchTenantCompany($domain);
    }

    public static function fetchApprovedTenantForDomain(string $domain) : ?TenantCompany
    {
        $tenant = static::fetchTenantForDomain($domain);
        
        if($tenant?->isApproved() ?? false)
        {
            return $tenant;
        }

        return  null;
    }

    public static function fetchTenantForDomain(string $domain) : ?TenantCompany
    {
        if(
            static::isItAdminPanelApp() 
            ||
            static::isItMonolithTenancyApp()
          )
        {
            return static::fetchTenantForServerSide($domain);
        }

        return static::fetchTenantForClientSide($domain);
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