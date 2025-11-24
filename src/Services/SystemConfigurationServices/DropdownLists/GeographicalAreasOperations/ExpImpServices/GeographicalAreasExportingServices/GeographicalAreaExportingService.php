<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\GeographicalAreasExportingServices;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListExportingService;

class GeographicalAreaExportingService extends DropDownListExportingService
{

    protected function initPdfExporter() : PDFExporter
    {
        return new GeographicalAreasPDFExporter();
    }

    protected function initCSVExporter() : CSVExporter
    {
        return new GeographicalAreasCSVExporter();
    }
}