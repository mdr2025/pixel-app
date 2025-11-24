<?php

namespace Database\Seeders\TenantDatabaseConfiguringSeeder;


use PixelApp\Database\Seeders\GeneralSeeders\BaseDropDownListModulesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\PermissionsSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\RolesSeeder;
use Illuminate\Database\Seeder;

/**
 * This seeder can be used for : tenant app database seeding while configuring tenant database
 * and it called on central app then moves to tenant app context to run the seeder classes there
 */
class TenantDatabaseConfiguringSeeder extends Seeder
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
