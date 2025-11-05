<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToDepartmentMethods
{
     /**
     * Department Type Constants
     */
    public const DEP_TYPES = ['manager', 'rep'];
    public const DEP_TYPE_MANAGER = 'manager';
    public const DEP_TYPE_REP = 'rep';

    public function department(): BelongsTo
    {
        $departmentClass = PixelModelManager::getModelForModelBaseType(Department::class);
        return $this->belongsTo($departmentClass)->select('id', 'name' , 'status' , 'is_default' , 'branch_id');
    }

    public function getDepartmentPropChanger() : UserSensitivePropChanger
    {
        return new DepartmentChanger();
    }

    protected function appendBranchFields() : void
    {
        $this->injectDepartmentFillables();
        $this->appendDepartmentIdCast();
    }

    protected function appendDepartmentIdCast() : void
    {
        $this->casts['department_id'] = 'integer';
    }

    protected function injectDepartmentFillables() : void
    {
        $this->fillable['department_id'] = 'department_id';
        $this->fillable['dep_role'] = 'dep_role';
    }


}