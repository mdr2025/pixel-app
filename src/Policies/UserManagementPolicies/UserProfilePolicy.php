<?php

namespace PixelApp\Policies\UserManagementPolicies;

use PixelApp\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class UserProfilePolicy extends BasePolicy
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
    public function create(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_umm-users-list")
                                                   ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck( "edit_umm-users-list")
                                                   ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck( "delete_umm-users-list")
                                                   ->hasPermissionsOrFail();
    }


}
