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

         $mainBranch = new Branch([
            'name' => Branch::getMainBranchName(),
            'type' => 'main',
            'country_id' => tenant()->country_id,
        ]);

        $mainBranch->save();
    }
}
