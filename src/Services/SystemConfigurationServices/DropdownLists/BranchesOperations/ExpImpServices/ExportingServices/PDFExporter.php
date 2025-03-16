<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListPDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\Tratis\ExporterQueryBuilderCustomization;

class PDFExporter extends DropDownListPDFExporter
{
    use ExporterQueryBuilderCustomization;

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }
 
}