<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs\UserCSVFileFormatFactory;

class UserTypeCSVFileFormatFactory extends UserCSVFileFormatFactory
{

    protected function getColumnFormatInfoCompoenents() : array
    {
        $columns = parent::getColumnFormatInfoCompoenents();
        $columns[] = (new CSVFormatColumnInfoComponent("G" , 'Department'))->setDatabaseFieldName('name')->relationshipColumn('department' , false);
        $columns[] = (new CSVFormatColumnInfoComponent("H" , 'Role'))->setDatabaseFieldName('name')->relationshipColumn('role' , false);
        return $columns;
    }
}