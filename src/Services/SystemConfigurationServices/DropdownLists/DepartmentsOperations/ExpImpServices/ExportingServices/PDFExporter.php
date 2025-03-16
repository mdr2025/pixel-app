<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListPDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\Tratis\ExporterQueryBuilderCustomization;

class PDFExporter extends DropDownListPDFExporter
{
    use ExporterQueryBuilderCustomization;

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }
 
}