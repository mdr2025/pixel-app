<?php

namespace PixelApp\Models\Traits\OptionalRelationsTraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager; 
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait MustHaveRoleMethods
{
    public function role(): BelongsTo
    {
        $roleModelClass = PixelModelManager::getRoleModelClass();
        return $this->belongsTo($roleModelClass , "role_id", "id");
    }

    public function getRolePropChanger() : UserSensitivePropChanger
    {
        return new UserRoleChanger();
    }
    
    protected function appendRoleFileds() : void
    {
        $this->injectRoleFillables();
        $this->appendRoleIdCast();
        $this->appendRleGuardedAttrs();
    }

    protected function injectRoleFillables() : void
    {
        $this->fillable['role_id'] = 'role_id';
        $this->fillable['previous_role_id'] = 'previous_role_id';
    }

    protected function appendRoleIdCast() : void
    { 
        $this->casts['role_id'] = 'integer';
    }
    
    protected function appendRleGuardedAttrs() : void
    {
        $this->guarded['role_id'] = 'role_id';
    }

    public function permissions(): array
    {
        return $this->role?->permissions->pluck("name")->toArray() ?? [];
    }

    public function HasPermission(string $permissionToCheck): bool
    {
        $userPermissions = $this->permissions();
        return in_array($permissionToCheck, $userPermissions);
    }
    
    /**
     * Scope to exclude super admin users
     */
    public function scopeNotSuperAdmin($query) : void
    {
        $query->where('role_id', '!=', 1);
    }
    
    /**
     * an alias to scopeNotSuperAdmin - for compability with exists applications
     */
    public function scopeWithoutSuperAdmin($query): void
    {
        $this->scopeNotSuperAdmin($query);
    }

}