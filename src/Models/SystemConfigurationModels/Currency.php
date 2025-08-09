<?php

namespace PixelApp\Models\SystemConfigurationModels;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Models\PixelBaseModel;

class Currency extends PixelBaseModel
{
    use HasFactory, SoftDeletes ;

    protected $table = "currencies";
    const ROUTE_PARAMETER_NAME = "currency";

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'symbol_native',
        'decimal_digits',
        'rounding',
        'name_plural',
        'is_main',
        "status"
    ];

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    protected $casts = [
        'status' => 'boolean',
    ];
}
