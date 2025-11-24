<?php

namespace PixelApp\Database\Seeders;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\NormalCompanyResetingSeeders\CompanyFullResettingSeeder;
use Database\Seeders\NormalCompanyResetingSeeders\CompanyPartialResetSeeder;
use Database\Seeders\TenantResettingSeeders\TenantCompanyFullResettingSeeder;
use Database\Seeders\TenantResettingSeeders\TenantCompanyPartialResettingSeeder;
use Database\Seeders\TenantDatabaseConfiguringSeeder\TenantDatabaseConfiguringSeeder;

class PixelSeedersManager
{

    protected static function initPixelSeedersStubManager() : PixelSeedersStubManager
    {
        return PixelSeedersStubManager::Singleton();
    }

    public static function installSeeders() : void
    {
        static::initPixelSeedersStubManager()->replaceSeederStubs();
    }

    public static function getDatabaseSeederClass() : string
    {
        return DatabaseSeeder::class;
    }

    public static function getCompanyFullResettingSeederClass() : string
    {
        return CompanyFullResettingSeeder::class;
    }

    public static function getCompanyPartialResetSeederClass() : string
    {
        return CompanyPartialResetSeeder::class;
    }

    public static function getTenantCompanyFullResettingSeederClass() : string
    {
        return TenantCompanyFullResettingSeeder::class;
    }

    public static function getTenantCompanyPartialResettingSeederClass() : string
    {
        return TenantCompanyPartialResettingSeeder::class;
    }

    public static function getTenantDatabaseConfiguringSeederClass() : string
    {
        return TenantDatabaseConfiguringSeeder::class;
    }
}