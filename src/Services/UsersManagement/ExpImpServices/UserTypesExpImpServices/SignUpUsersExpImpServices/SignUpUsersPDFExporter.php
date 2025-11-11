<?php

namespace PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices;


use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\Traits\ExporterQueryBuilderCustomization;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\UserPDFExporter;

class SignUpUsersPDFExporter extends UserPDFExporter
{
    use  ExporterQueryBuilderCustomization ;
   

}