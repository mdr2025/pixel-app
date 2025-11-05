<?php

namespace PixelApp\Models\ModelConfigs\DropdownLists\DepartmentsOperations;

use Spatie\QueryBuilder\AllowedFilter;

class DepartmentConfig
{
    public static function getFilters(): array
    {
        return [
            'name',
            'status',
            'branch.name',
            'branch_id'
        ];
    }
    public static function getFiltersForBranch(): array
    {
        return [
            'name',
            'departments.name',
            'status'
        ];
    }
    public static function getFiltersForList(): array
    {
        return [
            'name',
            AllowedFilter::exact('branch_id'),
        ];
    }
}
