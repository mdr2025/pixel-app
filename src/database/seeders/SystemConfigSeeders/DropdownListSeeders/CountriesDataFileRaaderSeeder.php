<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PixelApp\Config\PixelConfigManager;

class CountriesDataFileRaaderSeeder extends Seeder {

    protected function getCountriesJsonFilePath() : string
    {
        return realpath(__DIR__ . "/../../Data/countries.json");
    }

    protected function doesAppNeedSeeding() : bool
	{
		return PixelConfigManager::isCountriesFuncDefined();
	}

	public function run()
	{
		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

        $json = File::get($this->getCountriesJsonFilePath());
        $countries = json_decode($json, true);
        DB::table('countries')->insert($countries);
    }
}
