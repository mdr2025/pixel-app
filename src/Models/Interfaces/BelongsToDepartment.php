<?php

namespace PixelApp\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToDepartment
{
    public function department(): BelongsTo;
}