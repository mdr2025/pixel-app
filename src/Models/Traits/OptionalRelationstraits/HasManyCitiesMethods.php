<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Models\PixelModelManager;

trait HasManyCitiesMethods
{
    public function cities() : HasMany
    {
        $cityClass = PixelModelManager::getModelForModelBaseType(City::class);
        return $this->hasMany($cityClass);
    }
}