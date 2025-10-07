<?php

use PixelApp\Config\ConfigEnums\PixelAppTypeEnum;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;

return [ 
    "pixel-app-type" =>  PixelAppTypeEnum::DEFAULT_PIXEL_APP_TYPE,
    "pixel-app-package-route-registrars" => [
        // "dropdown-list" => [
        //     "areas" => AreasRouteRegistrar::class,
        //     "currencies" => CurrenciesRouteRegistrar::class,
        //     "branches" => BranchesRouteRegistrar::class,
        //     "cities" => CitiesRouteRegistrar::class,
        //     "countries" => CountriesRouteRegistrar::class,
        //     "departmens" => DepartmentRouteRegistrar::class,
        //     //"user-signature" => UserSignatureAPIRoutesRegistrar::class
        // ],

        // CompanyAuthenticationAPIRoutesRegistrar::class,
        // UserAuthenticationAPIRoutesRegistrar::class,
        
        // //PackagesRouteRegistrar::class,
        // RolesAndPermissionsRouteRegistrar::class,
        // CompanyBranchesAPIRoutesRegistrar::class,
        // CompanyProfileAPIRoutesRegistrar::class,
        // CompanySettingsAPIRoutesRegistrar::class,
        // UserProfileAPIRoutesRegistrar::class,
        // SignUpUsersAPIRoutesRegistrar::class,
        // UsersAPIRoutesRegistrar::class
    ], 
    /**
     * it only will be used on tenancy supporter app only (not normal app)
     * any alternative ServiceProvider must be a child class of 
     * PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider
     */
    "pixel-tenancy-service-provider-class" => TenancyServiceProvider::class,

];