<?php

namespace PixelApp\Models\ModelConfigs\UsersModule\SignUp;

use PixelApp\Filters\MultiFilters;
use Spatie\QueryBuilder\AllowedFilter;

class SignUpConfig
{
    public static function getRelations(): array
    {
        return [
            'profile',
            'profile.nationality',
            'branch.country',
            'department',
        ];
    }

    public static function getFilters(): array
    {
        return [
            AllowedFilter::custom('created_at', new MultiFilters(['created_at', 'accepted_at'])),
            AllowedFilter::exact('branch', 'branch.name'),
            AllowedFilter::exact('gender', 'profile.gender'),
            AllowedFilter::partial('national_id_number', 'profile.national_id_number'),
            AllowedFilter::partial('passport_number', 'profile.passport_number'),
            AllowedFilter::partial('nationality', 'profile.nationality.name'),
            AllowedFilter::scope('email_verified_at', 'emailVerified'),
            AllowedFilter::exact('status'),
            AllowedFilter::custom('name', new MultiFilters([
                'first_name',
                'last_name',
                'mobile',
                'email',
            ]))
        ];
    }
}
