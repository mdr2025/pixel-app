<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface HasManyAreas
{
    public function areas() : HasMany;
}