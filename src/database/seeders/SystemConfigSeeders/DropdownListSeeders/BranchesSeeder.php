<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

class BranchesSeeder extends Seeder
{
    protected function doesAppNeedSeeding() : bool
	{
		return PixelConfigManager::isBranchesFuncDefined();
	}

	protected function getBranchModelClass() : string
	{
		return PixelModelManager::getModelForModelBaseType(Branch::class);
	}

	protected function getHeadquarterBranchName() : string
	{
		$branchModelClass = $this->getBranchModelClass();
		return $branchModelClass::getHeadquarterBranchName();
	}

	public function run()
	{
		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

        Branch::create([ "name" => $this->getHeadquarterBranchName() ,"status" => 1 , "default" => 1 ]);
    }
}
