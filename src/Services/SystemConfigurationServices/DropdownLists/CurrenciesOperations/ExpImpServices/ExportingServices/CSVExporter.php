<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ExportingServices;

use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\Tratis\ExporterQueryAllowedFiltersSetting;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListCSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\Tratis\ExporterQueryBuilderCustomization;

class CSVExporter extends DropDownListCSVExporter
{
    use ExporterQueryBuilderCustomization , ExporterQueryAllowedFiltersSetting;
    
    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getModelForModelBaseType(Currency::class);
    }

    protected function getFormatFileName() : string
    {
        return "currencies";
    }

}