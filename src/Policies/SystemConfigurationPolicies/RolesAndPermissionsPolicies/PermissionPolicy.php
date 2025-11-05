<?php

namespace PixelApp\Policies\SystemConfigurationPolicies\RolesAndPermissionsPolicies;

use App\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class PermissionPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_permissions")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function create(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_permissions")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_permissions")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("delete_permissions")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function assign(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("assign_permissions")
            ->hasPermissionsOrFail();
    }
}
