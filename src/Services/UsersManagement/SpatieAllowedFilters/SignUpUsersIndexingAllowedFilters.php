<?php

namespace PixelApp\Services\UsersManagement\SpatieAllowedFilters;

use Illuminate\Database\Eloquent\Builder;
use PixelApp\Filters\MultiFilters;
use Spatie\QueryBuilder\AllowedFilter;

class SignUpUsersIndexingAllowedFilters
{
    public static function getFilters() : array
    {
       return  [
                    AllowedFilter::custom('created_at' , new MultiFilters(['created_at' , 'accepted_at'])),

                    "status",
                    AllowedFilter::exact("gender", "profile.gender"),
                    AllowedFilter::partial("national_id_number", "profile.national_id_number"),
                    AllowedFilter::partial("passport_number", "profile.passport_number"),
                    AllowedFilter::partial("country", "profile.country.name"),
                    AllowedFilter::callback('name', function (Builder $query, $value) {
                        $query->where('first_name', 'LIKE', "%{$value}%")
                            ->orWhere('last_name', 'LIKE', "%{$value}%")
                            ->orWhere('mobile', 'LIKE', "%{$value}%")
                            ->orWhere('email', 'LIKE', "%{$value}%");
                    })
                ];
    }
}