<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\Signature;

trait HasUserSignatureMethods
{
    protected function getSinatureModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Signature::class);
    }
 
    public function cities() : HasOne
    {
        $signatureClass = $this->getSinatureModelClass();
        return $this->hasOne($signatureClass);
    }
}