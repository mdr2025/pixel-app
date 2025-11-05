<?php

namespace PixelApp\Services\Repositories\QueryCustomizers;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

abstract class QueryCustomizer
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
    
    abstract public function customizeQuery(Builder | QueryBuilder $query) : self;
    abstract public function getResult() : mixed;
    
    public static function create() : self
    {
        return new static();
    }

}