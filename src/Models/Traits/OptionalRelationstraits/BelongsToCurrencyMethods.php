<?php

namespace PixelApp\Models\Traits\OptionalRelationstraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager; 
use PixelApp\Models\SystemConfigurationModels\Currency;

trait BelongsTocurrencyMethods
{
    protected function getCurrencyModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Currency::class);
    }
 
    public function currency(): BelongsTo
    {
        $currencyClass = $this->getCurrencyModelClass();
        return $this->belongsTo($currencyClass , 'currency_id');
    }

    protected function appendCurrencyIdCast() : void
    {
        $this->casts['currency_id'] = 'integer';
    }
}