<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

interface HasManyThroughCity
{
    public function areas() : HasManyThrough;
}