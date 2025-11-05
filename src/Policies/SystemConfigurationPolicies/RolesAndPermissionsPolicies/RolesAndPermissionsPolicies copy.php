<?php

namespace App\Policies\SystemSettings\SystemConfigurationPolicies\RolesAndPermissionsPolicies;

use App\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class RolesAndPermissionsPolicies extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_sc-roles-and-permissions")
                                                  ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function create() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_sc-roles-and-permissions")
                                                  ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_sc-roles-and-permissions")
                                                  ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function delete() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("delete_sc-roles-and-permissions")
                                                  ->hasPermissionsOrFail();
    }
}
