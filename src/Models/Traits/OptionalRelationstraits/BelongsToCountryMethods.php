<?php

namespace PixelApp\Models\Traits\OptionalRelationstraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager; 
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;

trait BelongsToCountryMethods
{
    protected function getCountryModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Country::class);
    }
 
    public function country(): BelongsTo
    {
        $countryClass = $this->getCountryModelClass();
        return $this->belongsTo($countryClass , 'country_id');
    }

    protected function appendCountryIdCast() : void
    {
        $this->casts['country_id'] = 'integer';
    }
}