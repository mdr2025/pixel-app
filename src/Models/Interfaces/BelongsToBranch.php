<?php

namespace PixelApp\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface BelongsToBranch
{
    public function branch() : BelongsTo;
}