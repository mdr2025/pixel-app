<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\BranchChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToBranchMethods
{ 
    public function branch() : BelongsTo
    {
        return $this->belongsTo( 
                                PixelModelManager::getModelForModelBaseType(Branch::class)
                               );
    }
 
    
    public function getBranchPropChanger() : UserSensitivePropChanger
    {
        return new BranchChanger();
    }

    protected function appendBranchIdCast() : void
    {
        $this->casts['branch_id'] = 'integer';
    }
}