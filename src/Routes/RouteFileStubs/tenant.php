<?php

declare(strict_types=1);
 
use Illuminate\Support\Facades\Route;
use PixelApp\Http\Middleware\TenancyCustomMiddlewares\ActiveTenantCompany;
use PixelApp\Http\Middleware\TenancyCustomMiddlewares\ApprovedTenantCompany;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;


/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    InitializeTenancyByDomainOrSubdomain::class,
    ActiveTenantCompany::class ,
    ApprovedTenantCompany::class,
    PreventAccessFromCentralDomains::class,
    'api'
])
->prefix('api')
->group(function () {

Route::get('/', function () {
    
        return 'This is a multi-tenant application. The id of the current tenant is ' . tenant('id') ?? "Unknown tenant";
    });
});
