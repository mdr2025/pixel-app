<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

interface BelongsToBranch
{
    public function branch() : BelongsTo;
    public function getBranchPropChanger() : UserSensitivePropChanger;
    public function accessibleBranches(): BelongsToMany;
    public function getAccessibleBranchesTableName() : string;
    public function getBranchForeignKeyName() : string;
    public function getPrimaryBranchIdAttribute(): ?int;
}