<?php

namespace PixelApp\Policies\UserManagementPolicies;

use App\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class SignupListPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_umm-signup-list")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_umm-signup-list")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function approve(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("approve_umm-signup-list")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function reject(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("reject_umm-signup-list")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function reVerify(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("re-verify_umm-signup-list")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function editEmail(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit-email_umm-signup-list")
            ->hasPermissionsOrFail();
    }
}
