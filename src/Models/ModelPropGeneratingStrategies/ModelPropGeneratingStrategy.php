<?php 

namespace PixelApp\Models\ModelPropGeneratingStrategies;

abstract class ModelPropGeneratingStrategy
{
    abstract public function generate() : mixed;
}