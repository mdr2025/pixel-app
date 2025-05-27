<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager; 
use PixelApp\Models\SystemConfigurationModels\Currency;

trait BelongsTocurrencyMethods
{
    public function currency(): BelongsTo
    {
        $currencyClass = PixelModelManager::getModelForModelBaseType(Currency::class);
        return $this->belongsTo($currencyClass , 'currency_id');
    }

    protected function appendCurrencyIdCast() : void
    {
        $this->casts['currency_id'] = 'integer';
    }
}