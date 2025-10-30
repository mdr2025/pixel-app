<?php

 
namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Traits;
 

trait ExporterQueryAllowedFiltersSetting
{
     
    protected function getDropDownListAllowedFilters() : array
    {
        return  [
                    'name',
                    'status'
                ];
    }

    protected function applySpatieAllowedFilters() : void
    {
        $allowedFilters = $this->getDropDownListAllowedFilters();
        $this->setSpatieBuilderAllowedFilters($allowedFilters);
        parent::applySpatieAllowedFilters();
    }
}