<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface HasManyGeographicalAreas
{
    public function areas() : HasMany;
}