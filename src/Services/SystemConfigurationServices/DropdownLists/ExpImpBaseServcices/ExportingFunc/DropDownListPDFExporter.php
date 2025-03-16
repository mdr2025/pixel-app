<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc;

use ExpImpManagement\ExportersManagement\Exporter\Traits\ExporterSelfConstructing;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter; 
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructableExporter;
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructablePDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Tratis\ExporterQueryBuilderCustomization;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Tratis\ExporterQueryAllowedFiltersSetting;
 
abstract class DropDownListPDFExporter extends PDFExporter implements SelfConstructableExporter , SelfConstructablePDFExporter
{
    use  ExporterSelfConstructing , ExporterQueryBuilderCustomization , ExporterQueryAllowedFiltersSetting;
   
    public function getViewRelevantPathForSelfConstructing() : string
    {
        return "pixel-app::Reports.PDFTemplates";
    }
}