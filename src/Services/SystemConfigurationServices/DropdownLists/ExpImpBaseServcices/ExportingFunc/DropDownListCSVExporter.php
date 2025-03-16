<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc;

use ExpImpManagement\ExportersManagement\Exporter\Traits\ExporterSelfConstructing;
use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\Interfaces\ExportsCSVImportableData;
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructableExporter;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Tratis\ExporterQueryBuilderCustomization;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Tratis\ExporterQueryAllowedFiltersSetting;

abstract class DropDownListCSVExporter extends CSVExporter implements SelfConstructableExporter , ExportsCSVImportableData
{
    use  ExporterSelfConstructing , ExporterQueryBuilderCustomization , ExporterQueryAllowedFiltersSetting;
   
    abstract protected function getFormatFileName() : string;

    public function getCSVImportableFileFormatFactory() : CSVImportableFileFormatFactory
    {
        return new DropDownListCSVFileFormatFactory($this->getFormatFileName());
    }
}