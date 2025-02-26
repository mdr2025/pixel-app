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
     * Apply filter for relationship columns
     *
     * @param Builder $query
     * @param string $column
     * @param mixed $value
     */
    protected function applyRelationFilter(Builder $query, string $column, $value): void
    {
        $parts = explode('.', $column);
        $relation = $parts[0];
        $actualColumn = $parts[1];
        $additionalColumn = $parts[2] ?? null;

        $query->orWhereHas($relation, function ($q) use ($actualColumn, $additionalColumn, $value) {
            if ($additionalColumn) {
                $this->applyNestedRelationFilter($q, $actualColumn, $additionalColumn, $value);
            } else {
                $q->where($actualColumn, 'like', $this->getLikePattern($value));
            }
        });
    }

    /**
     * Apply filter for nested relationships
     *
     * @param Builder $query
     * @param string $relation
     * @param string $column
     * @param mixed $value
     */
    protected function applyNestedRelationFilter(Builder $query, string $relation, string $column, $value): void
    {
        $query->whereHas($relation, function ($q) use ($column, $value) {
            $q->where($column, 'like', $this->getLikePattern($value));
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
