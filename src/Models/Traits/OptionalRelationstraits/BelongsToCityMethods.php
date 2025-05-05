<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;

trait BelongsToCityMethods
{
    protected function getCityModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(City::class);
    }
 
    public function city(): BelongsTo
    {
        $cityClass = $this->getCityModelClass();
        return $this->belongsTo($cityClass , 'city_id');
    }

    protected function appendCityIdCast() : void
    {
        $this->casts['city_id'] = 'integer';
    }
}