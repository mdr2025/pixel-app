<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces;

interface HasValidationRules
{
    public function getValidationRules(array $data = []) : array;
}
