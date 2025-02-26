<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use Exception;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class UserRoleChanger extends UserSensitivePropChanger implements ExpectsSensitiveRequestData
{
    use ExpectsSensitiveRequestDataFunc;
    protected ?RoleModel $activeNewRole = null;
    protected int $newRoleIdValue;


    public function getPropName() : string
    {
        return "role_id";
    }
    public function getPropRequestKeyDefaultName(): string
    {
        return "role_id";
    }

    protected function checkRoleChanging() : bool
    {
        return $this->newRoleIdValue != $this->authenticatable->{ $this->getPropName() };
    }

    /**
     * @param array $changes
     * @return array
     * @throws Exception
     */
    protected function appendUserPreviousRoleChanges(array $changes = [])  :array
    {
        if($this->checkRoleChanging() && $this->checkAuthenticatable())
        {
            $changes["previous_role_id"] = $this->authenticatable->{ $this->getPropName() };
        }
        return $changes;
    }

    protected function getUserRoleChanges(array $changes = []) : array
    {
        $changes[ $this->getPropName() ] = $this->activeNewRole->id;
        return $changes;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function setNewRoleIdValue() : void
    {
        if($this->activeNewRole)
        {
            $this->newRoleIdValue = $this->activeNewRole->id;
            return;
        }
        $this->newRoleIdValue = $this->data[ $this->getPropRequestKeyName() ] ?? 0;
    }

    /**
     * @param RoleModel $role
     * @return $this
     * @throws Exception
     */
    public function setNewActiveRole(RoleModel $role) : self
    {
        if(! $role->isActive())
        {
            throw new Exception("The provided role is not active role");
        }
        $this->activeNewRole = $role;
        $this->setNewRoleIdValue();
        return $this;
    }

    /**
     * @throws Exception
     */
    protected function fetchNewActiveRoleOrFail() : RoleModel
    {
        $activeNewRole = RoleModel::activeRole()->where("id", $this->newRoleIdValue )->select("id")->first();
        if(! $activeNewRole)
        {
            throw new Exception("The Given Role Is Not Exists In Our Database , Or is Not Active");
        }
        return $activeNewRole;
    }
    /**
     * @throws Exception
     * Here We Make Sure That Role Is Exists , and it is Active Role
     */
    protected function newRoleModelHandling(): void
    {
        if(!$this->activeNewRole)
        {
            $this->setNewRoleIdValue();
            if(!$this->newRoleIdValue)  { return; }

            $this->activeNewRole = $this->fetchNewActiveRoleOrFail();
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPropChangesArray(): array
    {
        $this->newRoleModelHandling();
        if($this->activeNewRole)
        {
            $changes = $this->getUserRoleChanges();
            return $this->appendUserPreviousRoleChanges($changes);
        }
        return [];
    }
}
