<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Package extends PixelBaseModel
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
