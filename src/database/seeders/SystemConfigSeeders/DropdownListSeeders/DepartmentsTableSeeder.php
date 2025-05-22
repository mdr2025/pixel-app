<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;

class DepartmentsTableSeeder extends Seeder
{
    protected function doesAppNeedSeeding() : bool
	{
		return PixelConfigManager::isDepartmensFuncDefined();
	}

	protected function getDepartmentModelClass() : string
	{
		return PixelModelManager::getModelForModelBaseType(Department::class);
	}

	protected function createNewDepartment(array $data) : Department
	{
		$modelClass = $this->getDepartmentModelClass();
		return $modelClass::create($data);
	}
	protected function getDefaultDepartments() : array
	{
		$modelClass = $this->getDepartmentModelClass();
		return $modelClass::getDefaultDepartments();
	}

	public function run()
	{
		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

        foreach ( $this->getDefaultDepartments() as $department )
        {
            $this->createNewDepartment([ "name" => $department , "status" => 1 ]);
        }
    }
}
