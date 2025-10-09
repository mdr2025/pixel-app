<?php

namespace PixelApp\Routes;
 
use PixelApp\Config\ConfigEnums\PixelAppSystemRequirementsCard;
use PixelApp\Config\PixelConfigManager; 
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars\CompanyAuthenticationAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars\UserAuthenticationAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\NormalCompanyAccountRouteRegistrars\NormalCompanySettingsAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\NormalCompanyAccountRouteRegistrars\NormalCompanyProfileAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars\TenantCompanyProfileAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars\TenantCompanyResourcesConfiguringAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\CompanyAccountRouteRegistrars\TenantCompanyAccountRouteRegistrars\TenantCompanySettingsAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\AreasRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\BranchesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\CitiesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\CountriesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\CurrenciesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\DepartmentRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\PackagesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\RolesAndPermissionsRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars\CompanyBranchesAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars\UserProfileAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars\UserSignatureAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\SignUpUsersAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\UsersAPIRoutesRegistrar;
use Throwable;

class PixelRouteManager
{

    public static function getDefinedRouteRegistrars() : array
    {
        return array_filter(
                        PixelConfigManager::getDefinedRouteRegistrars() ,
                        function($class)
                        {
                            return !is_null($class);
                        }
                    );
    }

    public static function getPackageAllRouteRegistrars() : array
    {
        return [

            //auth route registrars
            CompanyAuthenticationAPIRoutesRegistrar::class,
            UserAuthenticationAPIRoutesRegistrar::class,

            //company account route registrars
            NormalCompanyProfileAPIRoutesRegistrar::class,
            NormalCompanySettingsAPIRoutesRegistrar::class,
            TenantCompanyProfileAPIRoutesRegistrar::class,
            TenantCompanyResourcesConfiguringAPIRoutesRegistrar::class,
            TenantCompanySettingsAPIRoutesRegistrar::class,

            //system configuration route registrars
            AreasRouteRegistrar::class,
            BranchesRouteRegistrar::class,
            CitiesRouteRegistrar::class,
            CountriesRouteRegistrar::class,
            CurrenciesRouteRegistrar::class,
            DepartmentRouteRegistrar::class,
            PackagesRouteRegistrar::class,
            RolesAndPermissionsRouteRegistrar::class,

            //user account route registrars
            CompanyBranchesAPIRoutesRegistrar::class,
            UserProfileAPIRoutesRegistrar::class,
            UserSignatureAPIRoutesRegistrar::class,

            //user management route registrars
            SignUpUsersAPIRoutesRegistrar::class,
            UsersAPIRoutesRegistrar::class
        ];
    }

    public static function getCentralDomains(): array
    {
        return PixelTenancyManager::getCentralDomains();
    }

    public static function getTenancyMiddlewares() : array
    {
        return PixelTenancyManager::getTenantDefaultMiddlewares();
    }

    public static function loadAPIRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        PixelRouteBootingManager::loadAPIRoutes($callbackOnRouteRegistrar);
    }
    
    public static function loadWebRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        PixelRouteBootingManager::loadWebRoutes($callbackOnRouteRegistrar);
    }
    
    public static function loadPixelAppPackageRoutes(?callable $callbackOnRouteRegistrar = null) : void
    {
        PixelRouteBootingManager::loadPixelAppPackageRoutes($callbackOnRouteRegistrar);
    }
    
    public static function loadTenantRoutes() : void
    {
        PixelRouteBootingManager::loadTenantRoutes();
    }

    protected static function initPixelRoutesInstallingManager() : PixelRoutesInstallingManager
    {
        return PixelRoutesInstallingManager::Singlton();
    }

    public static function installPackageRoutes() : void
    {
        static::initPixelRoutesInstallingManager()->installPackageRoutes();   
    }
}
// separated admin panel = app without tenancy + company auth server  => needs routes without central domains because it is on a single domain
// separated tenant app => needs routes with central with central routes and company auth client != monolith
// tenant app with admin panel => needs routes with central routes and company auth server = monolith
// app without tenancy => deosn\'t need central routes or company auth