<?php

namespace PixelApp\Policies\UserAccountPolicies;

use PixelApp\Exceptions\ExceptionTypes\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class UserProfilePolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_profile")
            ->hasPermissionsOrFail();
    }

    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_profile")
            ->hasPermissionsOrFail();
    }
    
}
