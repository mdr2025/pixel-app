<?php

namespace PixelApp\Models\SystemConfigurationModels\CountryModule;

use PixelApp\Models\PixelBaseModel; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Database\Factories\SystemConfigurationFactories\CityFactory;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToCountry;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\HasManyAreas;
use PixelApp\Models\Traits\OptionalRelationstraits\BelongsToCountryMethods;
use PixelApp\Models\Traits\OptionalRelationstraits\HasManyAreasMethods;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeededFromChildes;

class City extends PixelBaseModel implements NeededFromChildes , BelongsToCountry , HasManyAreas
{

    use SoftDeletes, HasFactory , BelongsToCountryMethods , HasManyAreasMethods;


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

    public function __construct()
    {
        parent::__construct();
        
        $this->appendCountryIdCast();
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    protected static function newFactory()
    {
        return CityFactory::new();
    }
}
