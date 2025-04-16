<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountriesDataFileRaaderSeeder extends Seeder {

	 /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('../../Data/countries.json');
        $countries = json_decode($json, true);
        DB::table('countries')->insert($countries);
    }
}
