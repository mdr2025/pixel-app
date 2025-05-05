<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToArea
{
    public function area() : BelongsTo;
}