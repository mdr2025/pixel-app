<?php

namespace PixelApp\Database\Seeders\GeneralSeeders;

use Illuminate\Database\Seeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\BranchesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\CurrenciesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\DepartmentsTableSeeder;

class BaseDropDownListModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AllLocationDataDatabaseSeeder::class,
            BranchesSeeder::class,
            // DepartmentsTableSeeder::class,
            CurrenciesSeeder::class
        ]);
    }
}
