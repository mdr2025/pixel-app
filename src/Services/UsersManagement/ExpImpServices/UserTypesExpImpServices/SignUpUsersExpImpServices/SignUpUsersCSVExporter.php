<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices;
 
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\UserCSVExporter;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\UserCSVFileFormatFactory;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\Tratis\ExporterQueryBuilderCustomization;

class SignUpUsersCSVExporter extends UserCSVExporter
{
    use   ExporterQueryBuilderCustomization ;

    public function getModelClassForSelfConstructing() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function getUserCSVFileFormatFactory() : CSVImportableFileFormatFactory
    {
        return new UserCSVFileFormatFactory("signup_users");
    }


}