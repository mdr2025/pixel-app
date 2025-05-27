<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\Signature;

trait HasUserSignatureMethods
{
    public function signature() : HasOne
    {
        $signatureClass = PixelModelManager::getModelForModelBaseType(Signature::class);
        return $this->hasOne($signatureClass);
    }
}