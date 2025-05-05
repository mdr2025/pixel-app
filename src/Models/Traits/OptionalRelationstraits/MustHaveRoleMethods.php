<?php

namespace PixelApp\Models\Traits\OptionalRelationstraits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager; 
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

trait MustHaveRoleMethods
{
    protected function getRoleModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(RoleModel::class);
    }
 
    public function role(): BelongsTo
    {
        return $this->belongsTo($this->getRoleModelClass(), "role_id", "id");
    }

    public function getRolePropChanger() : UserSensitivePropChanger
    {
        return new UserRoleChanger();
    }
    
    protected function appendBranchIdCast() : void
    { 
        $this->casts['role_id'] = 'integer';
    }
}