<?php

use PixelApp\Config\ConfigEnums\PixelAppTypeEnum;
use PixelApp\PixelMacroableExtenders\PixelBlueprintExtender;
use PixelApp\PixelMacroableExtenders\PixelBuilderExtender;
use PixelApp\PixelMacroableExtenders\PixelCarbonExtender;
use PixelApp\PixelMacroableExtenders\PixelHasManyExtender;
use PixelApp\PixelMacroableExtenders\PixelReponseExtender;
use PixelApp\PixelMacroableExtenders\PixelStrExtender;
use PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars\CompanyAuthenticationAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\AuthenticationRoutesRegistrars\UserAuthenticationAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\AreasRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\BranchesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\CitiesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\CountriesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\DepartmentRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\PackagesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\RolesAndPermissionsRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\CompanyBranchesAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\CompanyProfileAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\CompanySettingsAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\SignUpUsersAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\UserProfileAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\UsersAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\UserSignatureAPIRoutesRegistrar;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;

return [ 
    "pixel-app-type" =>  PixelAppTypeEnum::TENANT_APP_TYPE,
    "pixel-app-package-route-registrars" => [
        CompanyAuthenticationAPIRoutesRegistrar::class,
        UserAuthenticationAPIRoutesRegistrar::class,
        AreasRouteRegistrar::class,
        BranchesRouteRegistrar::class,
        CitiesRouteRegistrar::class,
        CountriesRouteRegistrar::class,
        DepartmentRouteRegistrar::class,
        //PackagesRouteRegistrar::class,
        RolesAndPermissionsRouteRegistrar::class,
        CompanyBranchesAPIRoutesRegistrar::class,
        CompanyProfileAPIRoutesRegistrar::class,
        CompanySettingsAPIRoutesRegistrar::class,
        UserProfileAPIRoutesRegistrar::class,
        UserSignatureAPIRoutesRegistrar::class,
        SignUpUsersAPIRoutesRegistrar::class,
        UsersAPIRoutesRegistrar::class
    ], 
    "pixel-macroable-extenders" => [
        PixelBlueprintExtender::class,
        PixelBuilderExtender::class,
        PixelCarbonExtender::class,
        PixelHasManyExtender::class,
        PixelStrExtender::class,
        PixelReponseExtender::class
    ], 
    /**
     * it only will be used on tenancy supporter app only (not normal app)
     * any alternative ServiceProvider must be a child class of 
     * PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider
     */
    "pixel-tenancy-service-provider-class" => TenancyServiceProvider::class,
          
];