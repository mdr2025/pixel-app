<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager; 
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;

trait BelongsToCountryMethods
{
    public function country(): BelongsTo
    {
        $countryClass = PixelModelManager::getModelForModelBaseType(Country::class);
        return $this->belongsTo($countryClass , 'country_id');
    }

    protected function appendCountryIdCast() : void
    {
        $this->casts['country_id'] = 'integer';
    }
}