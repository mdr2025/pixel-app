<?php

namespace PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations;

use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\Repositories\QueryCustomizers\QueryCustomizer;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\DepartmentRepositoryInterface;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations\QueryCustomizers\DepartmentsGetter;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations\QueryCustomizers\DepartmentsGroupedByBranchGetter;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations\QueryCustomizers\DepartmentsLister;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentRepository implements DepartmentRepositoryInterface
{

    protected function getDepartmentModelClass() : string
    {
        return  PixelModelManager::getModelForModelBaseType(Department::class);
    }

    public function fetchDepartmentById(int $id) : ?Department
    {
        return $this->getDepartmentModelClass()::find($id);
    }

    public function fetchDepartmentByIdOrFail(int $id) : Department
    {
        return $this->getDepartmentModelClass()::findOrFail($id);
    }

    protected function initDepartmentsGettingQueryCustomizer()  :QueryCustomizer
    {
        return DepartmentsGetter::create();
    }

    public function getDepartments()
    {
        $query = $this->initDepartmentSpatieQueryBuilder();
        return $this->initDepartmentsGettingQueryCustomizer()->customizeQuery($query)->getResult();
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType( Branch::class );
    }

    protected function initDepartmentsGroupedByBranchGetter() : QueryCustomizer
    {
        return DepartmentsGroupedByBranchGetter::create();
    }

    public function getDepartmentsGroupByBranch()
    {
        $query = $this->initBranchSpatieQueryBuilder();
        return $this->initDepartmentsGroupedByBranchGetter()->customizeQuery($query)->getResult();
    }

    public function getCountActiveDepartments()
    {
        $departmentModelClass = $this->getDepartmentModelClass();

        return $departmentModelClass::active()->count();
    }

    protected function initDepartmentSpatieQueryBuilder() : QueryBuilder
    {
        $departmentModelClass = $this->getDepartmentModelClass();

        return QueryBuilder::for($departmentModelClass);
    }

    protected function initBranchSpatieQueryBuilder() : QueryBuilder
    {
        $branchModelClass = $this->getBranchModelClass();

        return QueryBuilder::for($branchModelClass);
    }

    protected function initDepartmentsListingQueryCustomizer()  : QueryCustomizer
    {
        return DepartmentsLister::create();
    }

    public function getListDepartments()
    {
        $query = $this->initDepartmentSpatieQueryBuilder();
        return $this->initDepartmentsListingQueryCustomizer()->customizeQuery($query)->getResult();
    }

    
    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    public function validateUsersBelongToDepartmentOrUnassigned(array $usersIds, Department $department): bool
    {
        $userModelClass = $this->getUserModelClass();

        $count = $userModelClass::whereIn('id', $usersIds)
                                ->where(function ($query) use ($department) {
                                    $query->where('department_id', $department->id)
                                        ->orWhereNull('department_id');
                                })
                                ->count();

        return $count === count($usersIds);
    }
}
