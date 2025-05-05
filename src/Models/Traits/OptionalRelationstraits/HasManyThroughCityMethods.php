<?php

namespace PixelApp\Models\Traits\OptionalRelationstraits;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Models\PixelModelManager;

trait HasManyThroughCityMethods
{
    protected function getCityModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(City::class);
    }

    protected function getAreaModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }

    public function areas() : HasManyThrough
    {
        $areaClass = $this->getAreaModelClass();
        $cityClass = $this->getCityModelClass();

        return $this->hasManyThrough($areaClass, $cityClass);
    }
}