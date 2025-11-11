<?php

namespace PixelApp\Services\Repositories\SystemSettings\SystemConfigurationRepositories\DropdownLists\BranchesOperations\QueryCustomizers;

use Illuminate\Database\Eloquent\Builder;
use PixelApp\Services\Repositories\QueryCustomizers\QueryCustomizer;
use Spatie\QueryBuilder\QueryBuilder;

class SubBranchesListter extends QueryCustomizer
{

    public function customizeQuery(Builder | QueryBuilder $query) : self
    { 
        $this->setQuery($query);
        return $this->setAllowedFilters()
                    ->orderRecords()
                    ->setQueryScopes();
    }
  
    protected function eagerLoadRelations() : self
    {
        $this->getQuery()->with('parent');
        return $this;
    }
    protected function setQueryScopes() : self
    {
        $this->getQuery()->scopes(['subBranch' ,'active' ]);
        return $this;
    }

    protected function orderRecords() : self
    {
        $this->getQuery()->customOrdering('created_at', 'desc');
        return $this;
    }

    protected function getSpatieAllowedFilters()  :array
    {
        return ['name', 'parent_id'];
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
        return  $this->getQuery()->get(['id', 'name']);
                            
    }
}