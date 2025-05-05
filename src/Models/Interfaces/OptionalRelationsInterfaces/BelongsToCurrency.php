<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToCurrency
{
    public function currency() : BelongsTo;
}