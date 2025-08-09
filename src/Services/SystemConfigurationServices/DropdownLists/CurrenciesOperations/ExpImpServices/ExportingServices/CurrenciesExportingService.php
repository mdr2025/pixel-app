<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ExportingServices;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter ;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ExportingServices\CSVExporter as CurrencyCSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ExportingServices\PDFExporter as CurrencyPDFExporter;

class CurrenciesExportingService extends DropDownListExportingService
{

    protected function initPdfExporter() : PDFExporter
    {
        return new CurrencyPDFExporter();
    }

    protected function initCSVExporter() : CSVExporter
    {
        return new CurrencyCSVExporter();
    }
}