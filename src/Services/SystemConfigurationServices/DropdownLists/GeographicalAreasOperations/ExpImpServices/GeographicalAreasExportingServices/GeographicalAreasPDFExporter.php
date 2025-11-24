<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\GeographicalAreasExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\Traits\ExporterQueryBuilderCustomization;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListPDFExporter;


class GeographicalAreasPDFExporter extends DropDownListPDFExporter
{
    use ExporterQueryBuilderCustomization;

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }
 
}