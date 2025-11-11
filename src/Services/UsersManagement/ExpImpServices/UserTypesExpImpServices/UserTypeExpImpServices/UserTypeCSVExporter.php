<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices\Traits\ExporterQueryBuilderCustomization;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\UserCSVExporter;

class UserTypeCSVExporter extends UserCSVExporter
{
    use   ExporterQueryBuilderCustomization;
  
    protected function getUserCSVFileFormatFactory() : CSVImportableFileFormatFactory
    {
        return new UserTypeCSVFileFormatFactory("users");
    } 
 
}