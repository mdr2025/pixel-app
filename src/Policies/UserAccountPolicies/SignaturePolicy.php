<?php

namespace PixelApp\Policies\UserAccountPolicies;

use PixelApp\Exceptions\ExceptionTypes\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class SignaturePolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_Signature")
            ->hasPermissionsOrFail();
    }

    public function create(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_Signature")
            ->hasPermissionsOrFail();
    }

    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_Signature")
            ->hasPermissionsOrFail();
    }

    
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edidelete_Signaturet_Signature")
            ->hasPermissionsOrFail();
    }
    
}
