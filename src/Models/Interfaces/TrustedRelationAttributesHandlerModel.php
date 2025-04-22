<?php

namespace PixelApp\Models\Interfaces;


interface TrustedRelationAttributesHandlerModel
{ 
    public function handleRelationsAttrs(array $relations) : void;
}