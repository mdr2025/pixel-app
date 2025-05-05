<?php

namespace PixelApp\Models\Traits\OptionalRelationstraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;

trait BelongsToAreaMethods
{
    protected function getAreaModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Area::class);
    }
 
    public function area(): BelongsTo
    {
        $areaClass = $this->getAreaModelClass();
        return $this->belongsTo($areaClass , 'area_id')->select('id', 'name' , 'city_id' , 'status');
    }

    protected function appendAreaIdCast() : void
    {
        $this->casts['area_id'] = 'integer';
    }
}