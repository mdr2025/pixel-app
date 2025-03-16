<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\Tratis;
 
trait ExporterQueryBuilderCustomization
{
     
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status' , 'default']);
    }
    

}