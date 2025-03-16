<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListCSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\Tratis\ExporterQueryBuilderCustomization;

class CSVExporter extends DropDownListCSVExporter
{
    use ExporterQueryBuilderCustomization;
    
    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }

    protected function getFormatFileName() : string
    {
        return "departments";
    }
}