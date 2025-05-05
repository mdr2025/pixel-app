<?php

namespace PixelApp\Models\Interfaces\OptionalRelationsInterfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface HasUserSignature
{
    public function signature() : HasOne;
}