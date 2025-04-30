<?php

namespace PixelApp\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserSensitivePropChanger;

interface MustHaveRole
{
    public function role() : BelongsTo;
    public function getRolePropChanger() : UserSensitivePropChanger;
}