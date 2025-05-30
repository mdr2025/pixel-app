<?php

namespace PixelApp\Models\SystemConfigurationModels\CountryModule;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Models\SystemAdminPanel\Company\CountryPackage; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Database\Factories\SystemConfigurationFactories\CountryFactory;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\HasManyCities;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\HasManyThroughCity;
use PixelApp\Models\Interfaces\TrustedAttributesHandlerModel;
use PixelApp\Models\Traits\OptionalRelationsTraits\HasManyCitiesMethods;
use PixelApp\Models\Traits\OptionalRelationsTraits\HasManyThroughCityMethods;
use PixelApp\Models\Traits\TrustedAttributesHandlerModelMethods;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;

class Country 
      extends PixelBaseModel
      implements NeededFromChildes , TrustedAttributesHandlerModel , HasManyCities , HasManyThroughCity
{

    //laravel traits
    use SoftDeletes, HasFactory;

    //pixel custom traits
    use TrustedAttributesHandlerModelMethods , HasManyCitiesMethods , HasManyThroughCityMethods;

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
    
    protected static function newFactory()
    {
        return CountryFactory::new();
    }

    // public function countryPackages()
    // {
    //     return $this->hasMany(CountryPackage::class);
    // }
}
