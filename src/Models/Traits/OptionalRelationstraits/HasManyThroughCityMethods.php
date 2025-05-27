<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Models\PixelModelManager;

trait HasManyThroughCityMethods
{
    public function areas() : HasManyThrough
    {
        $areaClass = PixelModelManager::getModelForModelBaseType(Area::class);
        $cityClass = PixelModelManager::getModelForModelBaseType(City::class);

        return $this->hasManyThrough($areaClass, $cityClass);
    }
}