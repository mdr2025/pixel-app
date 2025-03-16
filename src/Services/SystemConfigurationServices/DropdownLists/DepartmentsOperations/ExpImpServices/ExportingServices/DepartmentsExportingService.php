<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices;

use ExpImpManagement\ExportersManagement\ExporterTypes\CSVExporter\CSVExporter ;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ExportingFunc\DropDownListExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices\CSVExporter as DepartmentCSVExporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices\PDFExporter as DepartmentPDFExporter;

class DepartmentsExportingService extends DropDownListExportingService
{

    protected function initPdfExporter() : PDFExporter
    {
        return new DepartmentPDFExporter();
    }

    protected function initCSVExporter() : CSVExporter
    {
        return new DepartmentCSVExporter();
    }
}