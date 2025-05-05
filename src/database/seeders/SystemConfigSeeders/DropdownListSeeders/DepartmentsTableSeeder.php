<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\SystemConfigurationModels\Department;

class DepartmentsTableSeeder extends Seeder
{
    protected function doesAppNeedSeeding() : bool
	{
		return PixelConfigManager::isDepartmensFuncDefined();
	}

	public function run()
	{
		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

        foreach ( Department::getDefaultDepartments() as $department )
        {
            Department::create([ "name" => $department , "status" => 1 ]);
        }
    }
}
