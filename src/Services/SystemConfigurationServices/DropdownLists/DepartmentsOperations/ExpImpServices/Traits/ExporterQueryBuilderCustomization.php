<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\Tratis;
 
trait ExporterQueryBuilderCustomization
{ 
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status' , 'default' , 'parent_id']);
    }
    

}