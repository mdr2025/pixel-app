<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\GeographicalAreasExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListCSVExporter;

class GeographicalAreasCSVExporter extends DropDownListCSVExporter
{

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
    }

    protected function getFormatFileName() : string
    {
        return "geographical_areas";
    }
}