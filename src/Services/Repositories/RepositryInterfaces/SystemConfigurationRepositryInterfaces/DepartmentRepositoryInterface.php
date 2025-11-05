<?php

namespace PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces;

use PixelApp\Models\SystemConfigurationModels\Department;

interface DepartmentRepositoryInterface
{
    public function fetchDepartmentById(int $id) : ?Department;
    public function fetchDepartmentByIdOrFail(int $id) : Department;
    public function getDepartments();
    public function getDepartmentsGroupByBranch();
    public function getCountActiveDepartments();
    public function getListDepartments();
    public function validateUsersBelongToDepartmentOrUnassigned(array $usersIds, Department $department): bool;
}