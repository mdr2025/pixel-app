<?php

namespace PixelApp\Policies\AuthenticationPolicies\CompanyModulePolicies;

use PixelApp\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class CompanyModulePolicy extends BasePolicy
{
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
    public function create(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_company-account")
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
