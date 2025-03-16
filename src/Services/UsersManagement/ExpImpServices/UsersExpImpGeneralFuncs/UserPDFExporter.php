<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs;

use ExpImpManagement\ExportersManagement\Exporter\Traits\ExporterSelfConstructing;
use ExpImpManagement\ExportersManagement\ExporterTypes\PDFExporter\PDFExporter; 
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructableExporter;
use ExpImpManagement\ExportersManagement\Interfaces\SelfConstructablePDFExporter;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\Tratis\ExporterQueryAllowedFiltersSetting;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\Tratis\ExporterQueryBuilderCustomization;

class UserPDFExporter extends PDFExporter implements SelfConstructableExporter , SelfConstructablePDFExporter
{
    use  ExporterSelfConstructing , ExporterQueryBuilderCustomization , ExporterQueryAllowedFiltersSetting;
 
    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getUserModelClass();
    }
  
    public function getViewRelevantPathForSelfConstructing() : string
    {
        return "pixel-app::Reports.PDFTemplates";
    }

}