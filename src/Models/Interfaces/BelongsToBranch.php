<?php

namespace PixelApp\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

interface BelongsToBranch
{
    public function branch() : BelongsTo;
    public function getBranchPropChanger() : UserSensitivePropChanger;
}