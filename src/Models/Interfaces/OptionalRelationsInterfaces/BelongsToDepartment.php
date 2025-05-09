<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

interface BelongsToDepartment
{
    public function department(): BelongsTo;
    public function getDepartmentPropChanger() : UserSensitivePropChanger;
}