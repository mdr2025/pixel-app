<?php

namespace Database\Seeders\GeneralSeeders;

use Illuminate\Database\Seeder;

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
//            CitiesSeeder::class,
//            AreasSeeder::class
        ]);
    }
}
