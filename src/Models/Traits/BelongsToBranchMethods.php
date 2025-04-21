<?php

namespace PixelApp\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;

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
 
    protected function appendBranchIdCast() : void
    {
        $this->casts['branch_id'] = 'integer';
    }
}