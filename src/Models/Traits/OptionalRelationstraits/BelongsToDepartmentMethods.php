<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToDepartment;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\DepartmentChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait BelongsToDepartmentMethods
{
     /**
     * Department Type Constants
     */
    public const DEP_TYPES = ['manager', 'rep' , 'engineer'];
    public const DEP_TYPE_MANAGER = 'manager';
    public const DEP_TYPE_REP = 'rep';

    protected function failIfDoesnotBelongsToDepartment() : void
    {
        if(!$this instanceof BelongsToDepartment)
        {
            throw new Exception("The class " . static::class . " Doesn't implement BelongsToDepartment interface ,  Can't include Department Funcinality into the class !" );
        }
    }

    public function department(): BelongsTo
    {
        $this->failIfDoesnotBelongsToDepartment();

        $departmentClass = PixelModelManager::getModelForModelBaseType(Department::class);
        return $this->belongsTo($departmentClass)->select('id', 'name' , 'status' , 'is_default' , 'branch_id');
    }

    public function getDepartmentPropChanger() : UserSensitivePropChanger
    {
        $this->failIfDoesnotBelongsToDepartment();

        return new DepartmentChanger();
    }

    protected function appendDepartmentFields() : void
    {
        $this->failIfDoesnotBelongsToDepartment();
        
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