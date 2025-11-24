<?php

namespace PixelApp\Policies\CompanyAccountPolicies;

use PixelApp\Exceptions\ExceptionTypes\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class CompanyAccountPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function changeAdminEmail(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("change-admin-email_company-account")
            ->hasPermissionsOrFail();
    }


    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_company-account")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_company-account")
            ->hasPermissionsOrFail();
    }
}
