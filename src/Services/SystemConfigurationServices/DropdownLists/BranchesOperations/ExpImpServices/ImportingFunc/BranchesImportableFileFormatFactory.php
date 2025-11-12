<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\ColumnDropDownListValue\ColumnDropDownListValueArrayHandler;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponentTypes\CSVBooleanSelectingColumnInfo;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponentTypes\CSVDropDownListColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\ListCellValidationSetter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

class BranchesImportableFileFormatFactory extends DropDownListCSVFileFormatFactory
{

    protected function getColumnFormatInfoCompoenents() : array
    {
        $columns = parent::getColumnFormatInfoCompoenents();
        // $columns[] = (new CSVDropDownListColumnInfoComponent("D" , "Branch Type (Main or Child)" , ColumnDropDownListValueArrayHandler::create()))
        // $columns[] = (new CSVBooleanSelectingColumnInfo("D" , "Default Branch" , "Yes" , "No"))->setDatabaseFieldName('default')
        //                                                                        ->setCellDataValidation(new ListCellValidationSetter(["0" , "1"]));

                                                                               
        $columns[] = (new CSVFormatColumnInfoComponent("E" , "Parent Branch Id"))->setDatabaseFieldName('branch_id');
        
        $columns[] = (new CSVFormatColumnInfoComponent("E" , "Country Id"))->setDatabaseFieldName('country_id');
        
        return $columns;
    }

}