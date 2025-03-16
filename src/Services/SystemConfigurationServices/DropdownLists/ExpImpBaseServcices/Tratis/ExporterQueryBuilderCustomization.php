<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\ExpImpBaseServcices\Tratis;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder; 

trait ExporterQueryBuilderCustomization
{
     
    protected function SelectColumns($builder) : void
    {
        $builder->select(['id', 'name', 'status']);
    }
   
    protected function initQueryBuilder() : Builder | DatabaseQueryBuilder | QueryBuilder
    {
        $builder = parent::initQueryBuilder(); 
        $this->SelectColumns($builder);
        return $builder;
    }
 

}