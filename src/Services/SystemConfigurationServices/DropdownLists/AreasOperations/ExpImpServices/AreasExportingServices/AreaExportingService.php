<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\AreasExportingServices;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListExportingService;

class AreaExportingService extends DropDownListExportingService
{

    protected function initPdfExporter() : PDFExporter
    {
        return new AreasPDFExporter();
    }

    protected function initCSVExporter() : CSVExporter
    {
        return new AreasCSVExporter();
    }
}