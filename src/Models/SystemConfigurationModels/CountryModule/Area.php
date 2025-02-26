<?php

namespace PixelApp\Models\SystemConfigurationModels\CountryModule;

use PixelApp\Models\PixelBaseModel ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Country\Database\factories\AreaFactory;
use PixelApp\Traits\HasTranslations;

class Area extends PixelBaseModel
{

    use SoftDeletes, HasTranslations, HasFactory;

    public $translatable = ['name'];
    const ROUTE_PARAMETER_NAME = "area";


    protected $fillable = [
        'name',
        'city_id',
        'status'
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    protected static function newFactory()
    {
        return AreaFactory::new();
    }
}
