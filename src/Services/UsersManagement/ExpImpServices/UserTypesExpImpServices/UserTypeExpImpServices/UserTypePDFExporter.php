<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices;
 
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices\Tratis\ExporterQueryBuilderCustomization;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\UserPDFExporter;

class UserTypePDFExporter extends UserPDFExporter
{
    use  ExporterQueryBuilderCustomization ;
   
}