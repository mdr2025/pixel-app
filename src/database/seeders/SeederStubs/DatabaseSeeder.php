<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;
use PixelApp\Database\Seeders\GeneralSeeders\BaseDropDownListModulesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\PermissionsSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\RolesAndPermissionsSeeders\RolesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(! $this->doesAppNeedsCentralDBPermissions())
        {
            return ;
        }
        
        $this->call([
            BaseDropDownListModulesSeeder::class,
            PermissionsSeeder::class,
            RolesSeeder::class
        ]);
    }

    protected function doesAppNeedsCentralDBPermissions() : bool
    {
        return !PixelAppBootingManager::isBootingForTenantApp();
    }
}
