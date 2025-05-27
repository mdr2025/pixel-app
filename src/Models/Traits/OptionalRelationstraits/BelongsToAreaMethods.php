<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;

trait BelongsToAreaMethods
{
    public function area(): BelongsTo
    {
        $areaClass = PixelModelManager::getModelForModelBaseType(Area::class);
        return $this->belongsTo($areaClass , 'area_id')->select('id', 'name' , 'city_id' , 'status');
    }

    protected function appendAreaIdCast() : void
    {
        $this->casts['area_id'] = 'integer';
    }
}