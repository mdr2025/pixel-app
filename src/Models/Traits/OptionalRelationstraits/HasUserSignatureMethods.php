<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\Signature;

trait HasUserSignatureMethods
{ 
    
    protected function getSignatureModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Signature::class);
    }

    public function signature() : HasOne
    {
        return $this->hasOne($this->getSignatureModelClass() , 'user_id', 'id');
    }
}