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
    
    /*
    |--------------------------------------------------------------------------
    | Refresh Token Grace Period
    |--------------------------------------------------------------------------
    |
    | This value defines how long *after* an access token expires, its associated
    | refresh token is still allowed to be used. After this grace period, the
    | refresh token should be deleted or ignored.
    |
    */
    'refresh_token_grace_period' => env('PASSPORT_REFRESH_GRACE_PERIOD', '10 days'),

    /*
    |--------------------------------------------------------------------------
    | Revoked Token Grace Period
    |--------------------------------------------------------------------------
    |
    | This value defines how long revoked access tokens can stay in the database
    | before being permanently deleted. Useful for audit or temporary revocation.
    |
    */
    'revoked_token_grace_period' => env('PASSPORT_REVOKED_GRACE_PERIOD', '10 days')


];