<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\ExpectsSensitiveRequestData;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\HasValidationRules;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits\ExpectsSensitiveRequestDataFunc;

class UserRoleChanger
      extends UserSensitivePropChanger
      implements ExpectsSensitiveRequestData , HasValidationRules
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

    public function getValidationRules() : array
    {
        return   ["required"  , "integer", "exists:roles,id"] ;
                
    }

    public function getSuperAdminRoleData() : array
    {
        return [ $this->getPropName() => 1 ];
    }

    public function assignAsSuperAdmin(Model $authenticatable)  
    {
        $this->setAuthenticatable($authenticatable)
             ->setData($this->getSuperAdminRoleData())
             ->changeAuthenticatableProp();
    }
 
    /**
     * @param Model|null $authenticatable
     * @return $this
     * @throws Exception
     */
    public function setAuthenticatable(Model $authenticatable): self
    { 
        if(! $authenticatable instanceof PixelUser)
        {
            throw new Exception("The user model wanted to change his role must be a child type of PixelApp\Models\UsersModule\PixelUser !");
        }

        return parent::setAuthenticatable($authenticatable);
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

    
    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(RoleModel::class);
    }

    /**
     * @throws Exception
     */
    protected function fetchNewActiveRoleOrFail() : RoleModel
    {
        $modelClass = $this->getRoleModeClass();

        $activeNewRole = $modelClass::activeRole()->where("id", $this->newRoleIdValue )->select("id")->first();
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
