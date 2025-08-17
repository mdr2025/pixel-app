<?php

namespace PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement;

use Exception;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;

abstract class RoleUsersManager
{
    protected RoleModel $role;
    protected array $roleUserIDS = [];

    public function __construct(RoleModel $role)
    {
        $this->role = $role;
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    abstract protected function setRoleUserIDS(): self;

    protected function sanitizeUserIDSManualArray(): array
    {
        return array_filter($this->roleUserIDS, function ($item) {
            return is_int($item);
        });
    }
    /**
     * @param array $userIDS
     * @return $this
     * This Method Can Be Used To Assign User To A Role ... But It Will Be Manually
     */
    protected function setRoleUserIDSManually(array $userIDS): self
    {
        $this->roleUserIDS = $this->sanitizeUserIDSManualArray() ?? [];
        return $this;
    }

    abstract public function updateUsersRole(): bool;
    abstract protected function initToSwitchFoundUsers(): bool;
    abstract protected function getJobClass(): string;

    protected function delegateOperationToJob(): bool
    {
        /**
         * @todo to check dispatch return type later for larave 12
         */
        $this->getJobClass()::dispatch($this)->beforeCommit();
        return true;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function switchRoleUsers(): bool
    {
        if (empty($this->roleUserIDS)) {
            $this->setRoleUserIDS();
        }
        
        //When No Related User Is There .... Nothing To Do
        if (empty($this->roleUserIDS)) {
            return true;
        }

        //Run All Side Operations ... Each Child Class Maybe need to execute side operations to make users switching operation ready to run
        $this->initToSwitchFoundUsers();
        if (count($this->roleUserIDS) > 50) {
            return  $this->delegateOperationToJob();
        }

        //Switching All Users To Default Role ( User Role) ... Then Setting Previous Role = The Deleted Or Disabled Role ID
        return $this->updateUsersRole();
    }
}
