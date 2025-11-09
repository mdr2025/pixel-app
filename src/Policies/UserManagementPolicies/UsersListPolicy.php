<?php

namespace PixelApp\Policies\UserManagementPolicies;

use App\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class UsersListPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_umm-users-list")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_umm-users-list")
            ->hasPermissionsOrFail();
    }
}
