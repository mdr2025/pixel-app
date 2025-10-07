<?php

namespace PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement;
  
use Exception;
use PixelApp\Jobs\RoleJobs\SwitchBackAllRolePreviousUsersJob;

class SwitchBackAllRolePreviousUsers extends RoleUsersManager
{

    protected function setRoleUserIDS(): self
    {
        $this->roleUserIDS = $this->getUserModelClass()::where("previous_role_id", $this->role->id)->pluck("id")->toArray();
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function updateUsersRole(): bool
    {
        $updatedData = ["role_id" => $this->role->id, "previous_role_id" => null];
        $updatingResult = $this->getUserModelClass()::whereIn("id", $this->roleUserIDS)->update($updatedData);
        
        if ($updatingResult)
        {
            return true;
        }
        throw new Exception("Failed To Switch Related Previous Users To The Role " . $this->role->name);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function initToSwitchFoundUsers(): bool
    {
        return true;
    }

    protected function getJobClass(): string
    {
        return SwitchBackAllRolePreviousUsersJob::class;
    }
}