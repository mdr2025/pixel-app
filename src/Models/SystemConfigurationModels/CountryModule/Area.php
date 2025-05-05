<?php

namespace PixelApp\Models\SystemConfigurationModels\CountryModule;

use PixelApp\Models\PixelBaseModel ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Database\Factories\SystemConfigurationFactories\AreaFactory;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToCity;
use PixelApp\Models\Traits\OptionalRelationsTraits\BelongsToCityMethods;
use PixelApp\Traits\HasTranslations;

class Area extends PixelBaseModel implements BelongsToCity
{

    use SoftDeletes, HasTranslations, HasFactory , BelongsToCityMethods;

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

    public function __construct()
    {
        parent::__construct();    
        
        $this->appendCityIdCast();
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    protected static function newFactory()
    {
        return AreaFactory::new();
    }
}
