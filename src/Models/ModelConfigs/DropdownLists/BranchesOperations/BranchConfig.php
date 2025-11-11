<?php

namespace PixelApp\Models\ModelConfigs\DropdownLists\BranchesOperations;

class BranchConfig
{
    public static function getRelations(): array
    {
        return [
            'parent',
            'country'
        ];
    }
    public static function getFilters(): array
    {
        return [
            'parent.name',
            'country.name',
            'name',
            'status'
        ];
    }
}
