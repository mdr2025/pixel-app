<?php

namespace Database\Seeders;
 
use PixelApp\Database\Seeders\GeneralSeeders\BaseDropDownListModulesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\PermissionsSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\RolesSeeder;
use Illuminate\Database\Seeder;

class CompanyResetSeeder extends Seeder
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
