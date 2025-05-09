<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Database\Seeders\GeneralSeeders\BaseDropDownListModulesSeeder;

class DatabaseSeeder extends Seeder
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
        ]);
    }
}
