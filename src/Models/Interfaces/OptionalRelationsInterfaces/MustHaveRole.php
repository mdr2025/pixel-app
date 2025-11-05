<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

interface MustHaveRole
{
    public function role() : BelongsTo;
    public function getRolePropChanger() : UserSensitivePropChanger;
    public function permissions(): array;
    public function HasPermission(string $permissionToCheck): bool;

    
     /**
     * Scope to exclude super admin users
     */
    public function scopeNotSuperAdmin($query) : void;
    
    /**
     * an alias to scopeNotSuperAdmin - for compability with exists applications
     */
    public function scopeWithoutSuperAdmin($query): void;

}