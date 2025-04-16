<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        try{
            $json = File::get('database/seeders/Data/cities.json');
            $cities = json_decode($json, true);
            DB::table('cities')->insert($cities);
        }catch(\Throwable $exception)
        {
            dd($exception->getMessage());
            //nothing to do
        }
    }
}
