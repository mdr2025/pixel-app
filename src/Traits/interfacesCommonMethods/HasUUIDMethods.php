<?php

namespace PixelApp\Traits\interfacesCommonMethods;

use PixelApp\Interfaces\HasUUID;
use Illuminate\Support\Str;

trait HasUUIDMethods
{
    protected function generateUUID() : void
    {
        if($this instanceof HasUUID)
        {
            if($this->hashed_id == null)
            {
                $this->setAttribute("hashed_id" ,Str::uuid()->toString() );
            }
        }
    }

}
