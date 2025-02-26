<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;


use Exception;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class DepartmentChanger extends UserSensitivePropChanger implements ExpectsSensitiveRequestData
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

    /**
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        $value = $this->department?->id ?? $this->getPropNewRequestValue();
        return $this->composeChangesArray( $value );
    }

}
