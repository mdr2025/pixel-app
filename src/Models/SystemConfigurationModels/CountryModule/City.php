<?php

namespace PixelApp\Models\SystemConfigurationModels\CountryModule;

use PixelApp\Models\PixelBaseModel;
use Database\Factories\CityFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;

class City extends PixelBaseModel implements NeededFromChildes
{

    use SoftDeletes,
        HasFactory;


    protected $fillable = [
        'name',
        'country_id',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    const ROUTE_PARAMETER_NAME = "city";

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    protected static function newFactory()
    {
        return CityFactory::new();
    }
}
