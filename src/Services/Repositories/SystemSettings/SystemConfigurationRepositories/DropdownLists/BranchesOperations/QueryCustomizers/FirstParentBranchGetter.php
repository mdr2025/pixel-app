<?php

namespace PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers;

use Illuminate\Database\Eloquent\Builder;
use PixelApp\Models\ModelConfigs\DropdownLists\BranchesOperations\BranchConfig;
use PixelApp\Services\Repositories\QueryCustomizers\QueryCustomizer;
use Spatie\QueryBuilder\QueryBuilder;

class FirstParentBranchGetter extends QueryCustomizer
{
    public function customizeQuery(Builder | QueryBuilder $query) : self
    {
        $this->setQuery($query);

        return $this->setAllowedFilters()
                    ->setConditions()
                    ->selectColumns()
                    ->setQueryScopes()
                    ->orderRecords()
                    ->eagerLoadRelations();
    }
 
    protected function selectColumns() : self
    {
        $this->getQuery()->select(['id', 'name', 'parent_id', 'country_id', 'type', 'status']);
        return $this;
    }
 
    protected function setConditions() : self
    {
        $this->getQuery()->whereNull('parent_id');

        return $this;
    }

    protected function eagerLoadRelations() : self
    {
        $this->getQuery()->with(BranchConfig::getRelations());
        return $this;
    }

    protected function orderRecords() : self
    {
        $this->getQuery()->customOrdering('created_at', 'asc');
        return $this;
    }

    protected function setQueryScopes() : self
    {
        $this->getQuery()->datesFiltering();
        return $this;
    }

    protected function getSpatieAllowedFilters()  :array
    {
        return BranchConfig::getFilters();
    }

    protected function setAllowedFilters() : self
    {
        if($this->getQuery() instanceof QueryBuilder)
        {
            $this->getQuery()
                 ->allowedFilters( $this->getSpatieAllowedFilters() );
        }

        return $this;
    }

    public function getResult(): mixed
    {
        return  $this->getQuery()->first();
    }
}