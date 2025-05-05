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
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\CurrenciesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\DepartmentRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\DropdownListRouteRegistrars\PackagesRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\SystemConfigurationRouteRegistrars\RolesAndPermissionsRouteRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars\CompanyBranchesAPIRoutesRegistrar; 
use PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars\UserProfileAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UserAccountRoutesRegistrars\UserSignatureAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\SignUpUsersAPIRoutesRegistrar;
use PixelApp\Routes\RouteRegistrarTypes\UsersManagementRoutesRegistrars\UsersAPIRoutesRegistrar;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;

return [ 
    "pixel-app-type" =>  PixelAppTypeEnum::DEFAULT_PIXEL_APP_TYPE,
    "pixel-app-package-route-registrars" => [
        "dropdown-list" => [
            "areas" => AreasRouteRegistrar::class,
            "currencies" => CurrenciesRouteRegistrar::class,
            "branches" => BranchesRouteRegistrar::class,
            "cities" => CitiesRouteRegistrar::class,
            "countries" => CountriesRouteRegistrar::class,
            "departmens" => DepartmentRouteRegistrar::class,
            //"user-signature" => UserSignatureAPIRoutesRegistrar::class
        ],

        CompanyAuthenticationAPIRoutesRegistrar::class,
        UserAuthenticationAPIRoutesRegistrar::class,
        
        //PackagesRouteRegistrar::class,
        RolesAndPermissionsRouteRegistrar::class,
        CompanyBranchesAPIRoutesRegistrar::class,
        CompanyProfileAPIRoutesRegistrar::class,
        CompanySettingsAPIRoutesRegistrar::class,
        UserProfileAPIRoutesRegistrar::class,
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