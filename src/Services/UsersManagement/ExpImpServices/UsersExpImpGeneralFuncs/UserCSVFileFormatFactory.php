<?php

namespace  PixelApp\Services\UsersManagement\ExpImpServices\UsersExpImpGeneralFuncs;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\CSVImportableFileFormatFactory\CSVImportableFileFormatFactory;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;

class UserCSVFileFormatFactory extends CSVImportableFileFormatFactory
{ 
    protected function getColumnFormatInfoCompoenents() : array
    {
        return [
            (new CSVFormatColumnInfoComponent("A" , 'Id'))->setDatabaseFieldName('id'),
            (new CSVFormatColumnInfoComponent("B" , 'First Name'))->setDatabaseFieldName('first_name'),
            (new CSVFormatColumnInfoComponent("C" , 'Last Name'))->setDatabaseFieldName('last_name'),
            (new CSVFormatColumnInfoComponent("D" , 'Name'))->setDatabaseFieldName('name'),
            (new CSVFormatColumnInfoComponent("E" , 'Email'))->setDatabaseFieldName('email'),
            (new CSVFormatColumnInfoComponent("F" , 'Mobile'))->setDatabaseFieldName('mobile')
        ];
    }
}