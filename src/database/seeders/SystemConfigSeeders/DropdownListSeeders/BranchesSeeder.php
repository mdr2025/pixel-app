<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

class BranchesSeeder extends Seeder
{
    protected function doesAppNeedSeeding() : bool
	{
		return PixelConfigManager::isBranchesFuncDefined();
	}

	public function run()
	{
		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

        Branch::create([ "name" => Branch::getHeadquarterBranchName() ,"status" => 1 , "default" => 1 ]);
    }
}
