<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Database\Seeders\GeneralSeeders\BaseDropDownListModulesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\PermissionsSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\RolesSeeder;

class TenantDatabaseSeeder extends Seeder
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
            RolesSeeder::class,
//            SignUpUserSeeder::class,
//            AcceptedUserSeeder::class,
//            
        ]);
    }
}
