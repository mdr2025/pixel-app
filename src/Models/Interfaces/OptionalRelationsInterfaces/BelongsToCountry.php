<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToCountry
{
    public function country() : BelongsTo;
}