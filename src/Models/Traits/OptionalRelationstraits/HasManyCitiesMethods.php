<?php

namespace PixelApp\Models\Traits\OptionalRelationstraits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Models\PixelModelManager;

trait HasManyCitiesMethods
{
    protected function getCityModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(City::class);
    }
 
    public function cities() : HasMany
    {
        $cityClass = $this->getCityModelClass();
        return $this->hasMany($cityClass);
    }
}