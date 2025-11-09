<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;

use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\ModelConfigs\DropdownLists\DepartmentsOperations\DepartmentConfig;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\DepartmentRepositoryInterface;
use PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations\DepartmentRepository;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices\CSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ImportingFunc\DepartmentsImporter;
use PixelApp\Traits\FileExport;

class DepartmentService
{
    public function __construct()
    {
    }

    protected function initDepartmentRepositry() : DepartmentRepositoryInterface
    {
        return app(DepartmentRepositoryInterface::class);
    }
    
    public function getDepartments(): array
    {
        return [
            'list' => $this->initDepartmentRepositry()->getDepartments(),
        ];
    }
    public function getDepartmentsGroupByBranch(): array
    {
        return [
            'list' => $this->initDepartmentRepositry()->getDepartmentsGroupByBranch(DepartmentConfig::getFiltersForBranch()),
        ];
    }
    public function getCountActiveDepartments()
    {
        return $this->initDepartmentRepositry()->getCountActiveDepartments();
    }
    public function getListDepartments()
    {
        return $this->initDepartmentRepositry()->getListDepartments();
    }
    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentStoringService::class);
        return (new $service())->create();
    }
    
    public function update(int $departmentId)
    {
        $department = $this->initDepartmentRepositry()->fetchDepartmentByIdOrFail($departmentId);
        $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentUpdatingService::class);
        return (new $service($department))->update();
    }

    public function destroy(int $departmentId)
    {
        $department = $this->initDepartmentRepositry()->fetchDepartmentByIdOrFail($departmentId);
        $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentDeletingService::class);
        return (new $service($department))->delete();
    }

    public function validateToAssignSupervisors($request, int $departmentId): bool
    {
        $department = $this->initDepartmentRepositry()->fetchDepartmentByIdOrFail($departmentId);

        // Get users ids from request
        $usersIds = $request->department_rep_ids ?? [];

        // Validate users belong to department or unassigned
        if (!empty($usersIds)) {
            $isValid = $this->initDepartmentRepositry()->validateUsersBelongToDepartmentOrUnassigned($usersIds, $department);

            if (!$isValid)
            {
                return false;
            }
        }

        return true;
    }

    public function import()
    {
        return (new DepartmentsImporter())->import();
    }
    
    public function export()
    {
        return (new CSVExporter())->exportUsingInternalFormatName();
    }

    public function downloadFileFormat()
    {
        return (new DepartmentsImporter())->downloadFormat();
    }
}
