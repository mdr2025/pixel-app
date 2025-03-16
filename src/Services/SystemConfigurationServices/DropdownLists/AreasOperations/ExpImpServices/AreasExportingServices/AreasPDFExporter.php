<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\AreasExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListCSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListPDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\Tratis\ExporterQueryBuilderCustomization;

class AreasPDFExporter extends DropDownListPDFExporter
{
    use ExporterQueryBuilderCustomization;

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }

    public function getViewRelevantPathForSelfConstructing() : string
    {
        return "";
    }
 
}