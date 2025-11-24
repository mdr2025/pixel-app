<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Models\PixelModelManager;

trait HasManyGeographicalAreasMethods
{
    public function areas() : HasMany
    {
        $areaClass = PixelModelManager::getModelForModelBaseType(Area::class);
        return $this->hasMany($areaClass);
    }
}