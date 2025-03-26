<?php

namespace PixelApp\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ArrayFilters implements Filter
{
    public string $relation;

    /**
     * @param string $relation
     */
    public function __construct(string $relation)
    {
        $this->relation = $relation;
    }

    public function __invoke(Builder $query, $value, string $property , )
    {
        if ($value === 'all'){
            return $query->get();
        }elseif (is_string($value)){

            return $query->whereHas($this->relation, function ($query) use ($property,$value) {
                $query->where($property, $value);
            })->get()??null ;
        }

        return $query->whereHas($this->relation, function ($query) use ($property,$value) {
            $query->whereIn($property, $value);
        })->get()??null ;
    }
}
