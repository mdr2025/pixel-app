<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToDepartmentMethods
{
    protected function getDepartmentModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Department::class);
    }
 
    public function department(): BelongsTo
    {
        $departmentClass = $this->getDepartmentModelClass();
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