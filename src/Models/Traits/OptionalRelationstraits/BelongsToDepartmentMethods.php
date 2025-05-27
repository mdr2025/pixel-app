<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToDepartmentMethods
{
    public function department(): BelongsTo
    {
        $departmentClass = PixelModelManager::getModelForModelBaseType(Department::class);
        return $this->belongsTo($departmentClass)->select('id', 'name' , 'parent_id' , 'status');
    }

    public function getDepartmentPropChanger() : UserSensitivePropChanger
    {
        return new DepartmentChanger();
    }

    protected function appendDepartmentIdCast() : void
    {
        $this->casts['department_id'] = 'integer';
    }
}