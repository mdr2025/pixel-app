<?php

namespace PixelApp\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;


class MultiFilters implements Filter
{
    /** @var array */
    protected array $columns;

    /**
     * Initialize the filter with specified columns
     *
     * @param array $columns Array of columns to filter by
     */
    public function __construct(array $columns = [])
    {
        $this->columns = $columns;
    }

    /**
     * Apply the filter to the query
     *
     * @param Builder $query
     * @param mixed $value
     * @param string $property
     * @return Builder
     */
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        if (empty($this->columns)) {
            return $this->applySingleColumnFilter($query, $property, $value);
        }

        return $this->applyMultiColumnFilter($query, $value);
    }

    /**
     * Apply filter for a single column
     *
     * @param Builder $query
     * @param string $property
     * @param mixed $value
     * @return Builder
     */
    protected function applySingleColumnFilter(Builder $query, string $property, $value): Builder
    {
        return $query->where($property, 'like', $this->getLikePattern($value));
    }

    /**
     * Apply filter across multiple columns
     *
     * @param Builder $query
     * @param mixed $value
     * @return Builder
     */
    protected function applyMultiColumnFilter(Builder $query, $value): Builder
    {
        return $query->where(function ($query) use ($value) {
            foreach ($this->columns as $column) {
                $this->processColumn($query, $column, $value);
            }
        });
    }

    /**
     * Process individual column for filtering
     *
     * @param Builder $query
     * @param string $column
     * @param mixed $value
     */
    protected function processColumn(Builder $query, string $column, $value): void
    {
        if ($this->isRelationColumn($column)) {
            $this->applyRelationFilter($query, $column, $value);
        } else {
            $this->applySimpleFilter($query, $column, $value);
        }
    }

    /**
     * Check if column involves a relationship
     *
     * @param string $column
     * @return bool
     */
    protected function isRelationColumn(string $column): bool
    {
        return strpos($column, '.') !== false;
    }

    /**
     * Apply filter for relationship columns, including nested relationships.
     * Supports any depth of nested relationships using recursion.
     *
     * @param Builder $query
     * @param string $column
     * @param mixed $value
     */
    protected function applyRelationFilter(Builder $query, string $column, $value): void
    {
        $parts = explode('.', $column);

        if (count($parts) < 2) {
            return; // Invalid format - must have at least relation.column
        }

        $this->buildNestedWhereHas($query, $parts, $value);
    }

    /**
     * Recursively build nested whereHas clauses for relationships of any depth.
     *
     * @param Builder $query
     * @param array $parts Array of relation names and final column name
     * @param mixed $value
     */
    protected function buildNestedWhereHas(Builder $query, array $parts, $value): void
    {
        $relation = array_shift($parts); // Get first relation
        $remainingParts = $parts; // Remaining relations + column

        $query->orWhereHas($relation, function ($q) use ($remainingParts, $value) {
            if (count($remainingParts) === 1) {
                // Base case: last part is the column
                $q->where($remainingParts[0], 'like', $this->getLikePattern($value));
            } else {
                // Recursive case: more relations to traverse
                $this->buildNestedWhereHas($q, $remainingParts, $value);
            }
        });
    }

    /**
     * Apply simple column filter
     *
     * @param Builder $query
     * @param string $column
     * @param mixed $value
     */
    protected function applySimpleFilter(Builder $query, string $column, $value): void
    {
        $query->orWhere($column, 'like', $this->getLikePattern($value));
    }

    /**
     * Get the LIKE pattern for SQL
     *
     * @param mixed $value
     * @return string
     */
    protected function getLikePattern($value): string
    {
        return '%' . $value . '%';
    }
}
