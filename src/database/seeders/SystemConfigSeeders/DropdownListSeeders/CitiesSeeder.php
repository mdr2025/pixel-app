<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PixelApp\Config\PixelConfigManager;

class CitiesSeeder extends Seeder
{

    protected function getCitiesJsonFilePath() : string
    {
        return realpath(__DIR__ . "/../../Data/cities.json");
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
            
            $json = File::get($this->getCitiesJsonFilePath());
            $cities = json_decode($json, true);
            DB::table('cities')->insert($cities);
        }catch(\Throwable $exception)
        {
            dd($exception->getMessage());
            //nothing to do
        }
    }
}
