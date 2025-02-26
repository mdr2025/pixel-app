<?php

namespace PixelApp\Policies\AuthenticationPolicies\UsersModulePolicies;

use PixelApp\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class UsersModulePolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function readSignUpList() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_umm-signup-list")
                                                  ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function readEmployees() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_umm-users-list")
                                                  ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function createSignUpUsers() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_umm-signup-list")
                                                  ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function createUsers() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_umm-users-list")
                                                  ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function editSignUpUsers() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_umm-signup-list")
                                                  ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function editEmployees() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_umm-users-list")
                                                  ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function approveSignUpUsers() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("approve_umm-signup-list")
                                                  ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function rejectSignUpUsers() : bool
    {
        return $this->permissionExaminer->addPermissionToCheck("reject_umm-signup-list")
                                                  ->hasPermissionsOrFail();
    }
}
