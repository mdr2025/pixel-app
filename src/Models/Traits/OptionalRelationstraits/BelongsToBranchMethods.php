<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\BranchChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToBranchMethods
{
    protected function getBranchModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Branch::class);
    }

    public function branch() : BelongsTo
    {
        return $this->belongsTo( $this->getBranchModelClass() );
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