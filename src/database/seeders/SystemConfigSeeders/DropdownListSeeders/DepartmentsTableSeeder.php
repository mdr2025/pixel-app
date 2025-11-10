<?php

namespace PixelApp\Database\Seeders\SystemConfigSeeders\DropdownListSeeders;
 
use Illuminate\Database\Seeder;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
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
	protected function getDefaultDepartmentsConfigs() : array
	{
		$modelClass = $this->getDepartmentModelClass();
		return $modelClass::getDefaultDepartmentsConfigs();
	}

	protected function getMainBranchId() : int
	{
		$class = PixelModelManager::getModelForModelBaseType(Branch::class);
		return (new $class())->findMainBranch()->getKey();

	}

	protected function processDataRow(array $data) : array
	{
		return [
			"name" => $data['name'],
			"status" => $data['status'],
			"is_default" => $data['is_default'],
			"branch_id" => $this->getMainBranchId()
		];
	}

	public function run()
	{
		if(!$this->doesAppNeedSeeding())
		{
			return ;
		}

        foreach ( $this->getDefaultDepartmentsConfigs() as $department )
        {
			$branchId = $this->getMainBranchId();
			$department = $this->processDataRow($department);
			$department['branch_id'] = $branchId;

			$this->createNewDepartment( $department );
        }
    }
}
