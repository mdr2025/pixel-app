<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;


use Exception;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Rules\DepartmentBelongsToBranch;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasValidationRules;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class DepartmentChanger
      extends UserSensitivePropChanger
      implements ExpectsSensitiveRequestData , HasValidationRules
{
    use ExpectsSensitiveRequestDataFunc;
    protected ?Department $department = null;

    /**
     * @param Department|null $department
     * @return $this
     * @throws Exception
     */
    public function setDepartment(?Department $department = null): self
    {
        if(!$department->isActive())
        {
            throw new Exception("The provided department is not active");
        }
        $this->department = $department;
        return $this;
    }

    public function getPropName() : string
    {
        return 'department_id';
    }
    public function getPropRequestKeyDefaultName(): string
    {
        return 'department_id';
    }

    public function getValidationRules(array $data = []) : array
    {
        $branchId = $data["branch_id"] ?? null;
        if(!$branchId)
        {
            throw new Exception("The selected department is not dound in the branch .... Can't set the user to the selected department !");
        }

        return  [
                    $this->getPropName() => [ "required"  , "integer", "exists:departments,id" , new DepartmentBelongsToBranch($branchId)],

                ];
    }

    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        $value = $this->department?->id ?? $this->getPropNewRequestValue();
        // if department changed, set dep_role to null directly on the user
        if ($this->authenticatable && $this->authenticatable->department_id != $value) {
            $this->authenticatable->dep_role = null;
        }

        return $this->composeChangesArray( $value );
    }

}
