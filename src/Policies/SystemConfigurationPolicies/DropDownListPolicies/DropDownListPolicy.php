<?php

namespace PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies;

use PixelApp\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class DropDownListPolicy extends BasePolicy
{

    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_sc-dropdown-lists")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function create(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("create_sc-dropdown-lists")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_sc-dropdown-lists")
            ->hasPermissionsOrFail();
    }
    /**
     * @return bool
     * @throws JsonException
     */
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("delete_sc-dropdown-lists")
            ->hasPermissionsOrFail();
    }
}
