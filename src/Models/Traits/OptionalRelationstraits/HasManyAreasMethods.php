<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Models\PixelModelManager;

trait HasManyAreasMethods
{
    protected function getAreaModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }
 
    public function areas() : HasMany
    {
        $areaClass = $this->getAreaModelClass();
        return $this->hasMany($areaClass);
    }
}