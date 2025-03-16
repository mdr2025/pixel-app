<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter ;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices\CSVExporter as BranchCSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices\PDFExporter as BranchPDFExporter;

class BranchesExportingService extends DropDownListExportingService
{

    protected function initPdfExporter() : PDFExporter
    {
        return new BranchPDFExporter();
    }

    protected function initCSVExporter() : CSVExporter
    {
        return new BranchCSVExporter();
    }
}