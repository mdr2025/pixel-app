<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\Traits;
 
trait ExporterQueryBuilderCustomization
{ 
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status' , 'is_default' , 'branch_id']);
    }
    

}