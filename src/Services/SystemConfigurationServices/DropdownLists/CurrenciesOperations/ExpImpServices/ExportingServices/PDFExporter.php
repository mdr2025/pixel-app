<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\Traits\ExporterQueryAllowedFiltersSetting;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListPDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\Traits\ExporterQueryBuilderCustomization;

class PDFExporter extends DropDownListPDFExporter
{
    use ExporterQueryBuilderCustomization , ExporterQueryAllowedFiltersSetting;

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }
 
}