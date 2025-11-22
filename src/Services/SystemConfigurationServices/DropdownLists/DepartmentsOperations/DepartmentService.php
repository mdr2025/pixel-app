<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations;


use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\Repositories\RepositryInterfaces\SystemConfigurationRepositryInterfaces\DepartmentRepositoryInterface;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices\CSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ImportingFunc\DepartmentsImporter;

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
            'list' => $this->initDepartmentRepositry()->getDepartmentsGroupByBranch(),
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
