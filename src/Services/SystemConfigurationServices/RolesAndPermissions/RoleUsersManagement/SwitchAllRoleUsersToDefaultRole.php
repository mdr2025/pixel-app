<?php

namespace PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement;

use Exception;
use PixelApp\Jobs\RoleJobs\SwitchAllRoleUsersToDefaultRoleJob;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;

class SwitchAllRoleUsersToDefaultRole extends RoleUsersManager
{

    private string $DefaultRoleName ;
    private ?RoleModel $DefaultRoleModel = null;

    public function __construct(RoleModel $role)
    {
        parent::__construct($role);
        $this->setDefaultRoleName();
    }

    protected function setDefaultRoleName() : void
    {
        $this->DefaultRoleName = RoleModel::getLowestRoleName();
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function setDefaultRole(): self
    {
        $defaultRole = RoleModel::where("name",  $this->DefaultRoleName)->select("id")->first();
        if (!$this->DefaultRoleModel = $defaultRole)
        {
            throw new Exception("There Is No Default Role Can Be Used To Switching " . $this->role->name . "'s  Related Users");
        }
        return $this;
    }
    
    protected function setRoleUserIDS(): self
    {
        $this->roleUserIDS = $this->getUserModelClass()::where("role_id", $this->role->id)->pluck("id")->toArray();
        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function updateUsersRole(): bool
    {
        $updatedData = ["role_id"  => $this->DefaultRoleModel->id, "previous_role_id" => $this->role->id];
        if ($this->getUserModelClass()::whereIn("id", $this->roleUserIDS)->update($updatedData))
        {
            return true;
        }
        throw new Exception("Failed To Switch Related Users To The Default Role " . $this->DefaultRoleName);
    }

    /**
     * @return bool
     * @throws Exception
     */
    protected function initToSwitchFoundUsers(): bool
    {
        //Getting Default Role When Made Sure Of Related Users Foundability
        $this->setDefaultRole();
        return true;
    }

    protected function getJobClass(): string
    {
        return SwitchAllRoleUsersToDefaultRoleJob::class;
    }
}
