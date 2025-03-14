<?php

namespace PixelApp\Models\SystemConfigurationModels\CountryModule;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Models\SystemAdminPanel\Company\CountryPackage;
use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;

class Country extends PixelBaseModel implements NeededFromChildes
{

    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'code'
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
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function areas()
    {
        return $this->hasManyThrough(Area::class, City::class);
    }

    protected static function newFactory()
    {
        return CountryFactory::new();
    }

    // public function countryPackages()
    // {
    //     return $this->hasMany(CountryPackage::class);
    // }
}
