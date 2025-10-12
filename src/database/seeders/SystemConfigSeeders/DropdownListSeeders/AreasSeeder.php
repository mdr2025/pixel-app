<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PixelApp\Config\PixelConfigManager;

class AreasSeeder extends Seeder
{
    protected function getAreasJsonFilePath() : string
    {
        return realpath(__DIR__ . "/../../Data/areas.json");
    }

    
	protected function doesAppNeedSeeding() : bool
	{
		return PixelConfigManager::isCountriesFuncDefined() && PixelConfigManager::isCitiesFuncDefined();
	}

	public function run()
	{

        return; //temporary until solving cities data problem

		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

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
