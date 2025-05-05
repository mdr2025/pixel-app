<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToCity
{
    public function city() : BelongsTo;
}