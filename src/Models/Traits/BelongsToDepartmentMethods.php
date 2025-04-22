<?php

namespace PixelApp\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;

trait BelongsToDepartmentMethods
{
    protected function getDepartmentModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }

    protected function appendDepartmentIdCast() : void
    {
        $this->casts['department_id'] = 'integer';
    }
}