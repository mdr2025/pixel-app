<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Database\Seeders\GeneralSeeders\AllLocationDataDatabaseSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\DepartmentsTableSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\PermissionsSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\RolesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\BranchesSeeder;

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
            AllLocationDataDatabaseSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class,
            DepartmentsTableSeeder::class,
            // BranchesSeeder::class,
//            SignUpUserSeeder::class,
//            AcceptedUserSeeder::class,
//            
        ]);
    }
}
