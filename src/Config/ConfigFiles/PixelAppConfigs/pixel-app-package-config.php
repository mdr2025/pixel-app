<?php

use PixelApp\Config\ConfigEnums\PixelAppTypeEnum;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider;
 
return [ 
    "pixel-app-type" =>  PixelAppTypeEnum::DEFAULT_PIXEL_APP_TYPE,
    "pixel-app-package-route-registrars" => [], 
    
    /**
     * it only will be used on tenancy supporter app only (not normal app)
     * any alternative ServiceProvider must be a child class of 
     * PixelApp\ServiceProviders\RelatedPackagesServiceProviders\TenancyServiceProvider
     */
    "pixel-tenancy-service-provider-class" => TenancyServiceProvider::class,
    "user-model-class" => PixelUser::class,
    "tenant-company-model-class" => TenantCompany::class
];