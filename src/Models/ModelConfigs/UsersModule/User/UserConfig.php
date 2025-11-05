<?php

namespace PixelApp\Models\ModelConfigs\UsersModule\User;

use PixelApp\Filters\MultiFilters;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class UserConfig
{
    public static function getRelations(): array
    {
        return [
            'profile',
            'profile.nationality',
            'role',
            'department',
            'branch.country',
            'accessibleBranches.country',
        ];
    }
    public static function getFilters(): array
    {
        return [
            AllowedFilter::custom('created_at', new MultiFilters(['created_at', 'accepted_at'])),
            AllowedFilter::exact('status', 'status'),
            AllowedFilter::exact("gender", "profile.gender"),
            AllowedFilter::partial("national_id_number", "profile.national_id_number"),
            AllowedFilter::partial("passport_number", "profile.passport_number"),
            AllowedFilter::partial("nationality", "profile.nationality.name"),
            AllowedFilter::partial("department", 'department.name'),
            AllowedFilter::custom("branch", new MultiFilters(['branch.name', 'branch.country.name'])),
            AllowedFilter::partial("role", 'role.name'),

            AllowedFilter::custom('name', new MultiFilters([
                'first_name',
                'last_name',
                'mobile',
                'email',
            ])),
            AllowedFilter::callback('accessibleBranches', function (Builder $query, $value) {
                $query->whereHas('accessibleBranches', function (Builder $query) use ($value) {
                    $query->where('name', 'LIKE', "%{$value}%");
                });
                $query->orWhereHas('accessibleBranches.country', function (Builder $query) use ($value) {
                    $query->where('name', 'LIKE', "%{$value}%");
                });
            }),
        ];
    }
    public static function getUsersListFilters(): array
    {
        return [
            AllowedFilter::custom('name', new MultiFilters(['first_name', 'last_name', 'mobile', 'email',])),
            AllowedFilter::exact('branch_id'),
            AllowedFilter::callback('department_id', function (Builder $query, $value) {
                $value = is_string($value) ? [$value] : $value;
                $query->whereIn('department_id', $value);
            }),
        ];
    }
    public static function getUsersListRelations(): array
    {
        return [
            'profile:user_id,picture',
            'branch',
        ];
    }
    public static function getFilterUsersByBranch(): array
    {
        return [
            AllowedFilter::partial('branch_id', 'branch_id'),
        ];
    }
    public static function getDefaultUserFilters(): array
    {
        $searchableFields = ['first_name', 'last_name', 'mobile', 'email'];

        return [
            AllowedFilter::callback('name', function (Builder $query, $value) use ($searchableFields) {
                $query->where(function (Builder $q) use ($searchableFields, $value) {
                    $q->whereAny($searchableFields, 'LIKE', "%{$value}%");
                });
            }),
        ];
    }


}
