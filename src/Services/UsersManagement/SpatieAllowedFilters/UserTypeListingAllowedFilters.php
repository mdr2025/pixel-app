<?php

namespace PixelApp\Services\UsersManagement\SpatieAllowedFilters;

use Illuminate\Database\Eloquent\Builder; 
use Spatie\QueryBuilder\AllowedFilter;

class UserTypeListingAllowedFilters
{
    public static function getFilters() : array
    {
        return  [
                    AllowedFilter::callback('name', function (Builder $query, $value) {
                        $query->where('first_name', 'LIKE', "%{$value}%")
                            ->orWhere('last_name', 'LIKE', "%{$value}%")
                            ->orWhere('mobile', 'LIKE', "%{$value}%")
                            ->orWhere('email', 'LIKE', "%{$value}%");
                    })
                ];
    }
}