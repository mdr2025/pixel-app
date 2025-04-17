<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountriesDataFileRaaderSeeder extends Seeder {

    protected function getCountriesJsonFilePath() : string
    {
        return realpath(__DIR__ . "/../../Data/countries.json");
    }

	 /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get($this->getCountriesJsonFilePath());
        $countries = json_decode($json, true);
        DB::table('countries')->insert($countries);
    }
}
