<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

class ImportableFileFormatFactory extends DropDownListCSVFileFormatFactory
{

    protected function getColumnFormatInfoCompoenents() : array
    {
        $columns = parent::getColumnFormatInfoCompoenents();
        $columns[] = (new CSVFormatColumnInfoComponent("D" , "Default Branch"))->setDatabaseFieldName('default');
        return $columns;
    }

}