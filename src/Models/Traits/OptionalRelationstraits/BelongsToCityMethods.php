<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;

trait BelongsToCityMethods
{
    public function city(): BelongsTo
    {
        $cityClass = PixelModelManager::getModelForModelBaseType(City::class);
        return $this->belongsTo($cityClass , 'city_id');
    }

    protected function appendCityIdCast() : void
    {
        $this->casts['city_id'] = 'integer';
    }
}