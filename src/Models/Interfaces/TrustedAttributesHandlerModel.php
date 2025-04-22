<?php

namespace PixelApp\Models\Interfaces;


interface TrustedAttributesHandlerModel
{
    public function handleModelAttrs( array $attributes ) : void; 
}