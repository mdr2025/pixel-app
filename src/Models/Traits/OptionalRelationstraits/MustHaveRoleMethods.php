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
        $roleModelClass = PixelModelManager::getModelForModelBaseType(RoleModel::class);
        return $this->belongsTo($roleModelClass , "role_id", "id");
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