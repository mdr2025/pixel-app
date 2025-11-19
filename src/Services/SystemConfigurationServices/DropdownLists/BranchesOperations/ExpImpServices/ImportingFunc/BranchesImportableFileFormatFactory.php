<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc;

use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\ColumnDropDownListValue\ColumnDropDownListValueArrayHandler;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponentTypes\CSVBooleanSelectingColumnInfo;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\FormatColumnInfoComponents\CSVFormatColumnInfoComponentTypes\CSVDropDownListColumnInfoComponent;
use ExpImpManagement\ImportersManagement\ImportableFileFormatFactories\ValidationDataTypeSetters\CSVCellValidationDataTypeSetters\ListCellValidationSetter;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\ImportingFunc\DropDownListCSVFileFormatFactory;

class BranchesImportableFileFormatFactory extends DropDownListCSVFileFormatFactory
{
    protected function getColumnFormatInfoCompoenents(): array
    {
        $columns = parent::getColumnFormatInfoCompoenents();

        $columns[] = (new CSVDropDownListColumnInfoComponent('B', 'parent', ColumnDropDownListValueArrayHandler::create()->add_UserDisplay_DbValue_OptionsArray($parent)))->relationshipColumn("parent")->setDatabaseFieldName('parent_id');
        $columns[] = (new CSVDropDownListColumnInfoComponent('C', 'country', ColumnDropDownListValueArrayHandler::create()->add_UserDisplay_DbValue_OptionsArray($country)))->relationshipColumn("country")->setDatabaseFieldName('country_id');
        
        return $columns;
    }

    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    protected function getCountryModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Country::class);
    }

}