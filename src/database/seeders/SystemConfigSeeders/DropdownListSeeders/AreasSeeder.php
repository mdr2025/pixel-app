<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AreasSeeder extends Seeder
{
    protected function getAreasJsonFilePath() : string
    {
        return realpath(__DIR__ . "/../../Data/areas.json");
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $json = File::get($this->getAreasJsonFilePath());
            $areas = json_decode($json, true);
            // laravel collection bad thing
            $chunks = array_chunk($areas, 300);
            foreach ($chunks as $chunk)
            {
                DB::table('areas')->insert($chunk);
            }
        }catch(\Throwable $exception)
        {
            //nothing to do
        }

    }
}
