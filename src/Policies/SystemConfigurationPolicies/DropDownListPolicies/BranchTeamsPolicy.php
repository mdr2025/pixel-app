<?php

namespace PixelApp\Policies\SystemConfigurationPolicies\DropDownListPolicies;

use PixelApp\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class BranchTeamsPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_sc-dropdown-lists-branch-teams")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function readBranchTeams(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_sc-dropdown-lists-branch-teams-index")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function addTeam(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("add-team_sc-dropdown-lists-branch-teams")
            ->hasPermissionsOrFail();
    }
 
    /**
     * @return bool
     * @throws JsonException
     */
    public function edit(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("edit_sc-dropdown-lists-branch-teams")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("delete_sc-dropdown-lists-branch-teams")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function manageTeamMembers(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("manage-team-members_sc-dropdown-lists-branch-teams")
            ->hasPermissionsOrFail();
    }
}
