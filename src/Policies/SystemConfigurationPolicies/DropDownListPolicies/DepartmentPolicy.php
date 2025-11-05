<?php

namespace PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies;

use App\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class DepartmentPolicy extends BasePolicy
{

    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_sc-dropdown-lists-departments")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function create(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_sc-dropdown-lists-departments")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_sc-dropdown-lists-departments")
            ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("delete_sc-dropdown-lists-departments")
            ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function hasDepartmentAccess(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("department-rep_sc-dropdown-lists-departments")
            ->hasPermissionsOrFail();
    }
}
