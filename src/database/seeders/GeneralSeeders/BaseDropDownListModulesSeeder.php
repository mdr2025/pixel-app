<?php

namespace PixelApp\Database\Seeders\GeneralSeeders;

use Illuminate\Database\Seeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\BranchesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\CurrenciesSeeder;

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
            CurrenciesSeeder::class
        ]);
    }
}
