<?php

namespace PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\DepartmentsOperations\QueryCustomizers;

use Illuminate\Database\Eloquent\Builder;
use PixelApp\Models\ModelConfigs\DropdownLists\DepartmentsOperations\DepartmentConfig;
use PixelApp\Services\Repositories\QueryCustomizers\QueryCustomizer;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentsLister extends QueryCustomizer
{
    protected Builder | QueryBuilder | null $query = null;

    public function getQuery() : Builder | QueryBuilder | null 
    {
        return $this->query;
    }
    
    protected function setQuery( Builder | QueryBuilder $query) : self
    {
        $this->query = $query;
        return $this;
    }

    public function customizeQuery(Builder | QueryBuilder $query) : self
    {
        return $this->setQuery($query)
                    ->setAllowedFilters()
                    ->setQueryScopes()
                    ->eagerLoadRelations()
                    ->orderRocords();
    }

    protected function orderRocords() : self
    {
        $this->getQuery()->customOrdering('created_at', 'desc');
        return $this;
    }

    protected function eagerLoadRelations() : self
    {
        $this->getQuery()->with(['managers.profile', 'reps.profile']);
        return $this;
    }

    protected function setQueryScopes() : self
    {
        $this->getQuery()->scopes(['active']);
        return $this;
    }

    protected function getSpatieAllowedFilters()  :array
    {
        return DepartmentConfig::getFiltersForList();
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
        return $this->getQuery()->get(['id', 'name', 'branch_id', 'is_default']);    
    }
}