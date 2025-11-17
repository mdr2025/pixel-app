<?php

namespace PixelApp\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

class RelationshipColumnsFilter implements Filter
{

    public function __invoke(Builder $query, $value, string $property)
    {
        if (!Str::contains($property, '.')) {
            return $query->where($property,  $value);
        }

        $relations = explode('.', $property);
        $column = array_pop($relations); // Get the last element (column name)
        $currentRelation = array_shift($relations); // Get the first relation

        return $query->whereHas($currentRelation, function ($query) use ($relations, $column, $value,) {
            // For each intermediate relation
            foreach ($relations as $relation) {
                $query->whereHas($relation, function ($query) use ($relations, $relation, $column, $value) {
                    // If this is the last relation, apply the column filter
                    if ($relation === end($relations)) {
                        $query->where($column, $value);
                    }
                });
            }
        });
    }
}
