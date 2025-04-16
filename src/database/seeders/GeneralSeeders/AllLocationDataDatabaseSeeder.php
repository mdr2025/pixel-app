<?php

namespace PixelApp\Database\Seeders\GeneralSeeders;

use Illuminate\Database\Seeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\AreasSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\CitiesSeeder;
use PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders\CountriesSeeder;

class AllLocationDataDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesSeeder::class,
           CitiesSeeder::class,
           AreasSeeder::class
        ]);
    }
}
