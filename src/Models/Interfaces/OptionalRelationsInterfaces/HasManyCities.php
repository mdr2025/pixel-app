<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface HasManyCities
{
    public function cities() : HasMany;
}