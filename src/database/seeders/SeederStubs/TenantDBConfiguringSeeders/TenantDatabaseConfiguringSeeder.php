<?php

namespace Database\Seeders\TenantDatabaseConfiguringSeeder;

use Database\Seeders\TenantDatabaseSeeder;
use PixelApp\Database\Seeders\GeneralSeeders\BaseDropDownListModulesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\PermissionsSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\RolesSeeder;

class TenantDatabaseConfiguringSeeder extends TenantDatabaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
            BaseDropDownListModulesSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class
        ]);
    }
}
