<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;

class Package extends PixelBaseModel implements OnlyAdminPanelQueryable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'show',
        'monthly_price',
        'annual_price',
        'monthly_discount',
        'annual_discount',
        'privileges'
    ];
}
