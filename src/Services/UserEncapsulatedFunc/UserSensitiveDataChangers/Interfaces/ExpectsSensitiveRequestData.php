<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces;

interface ExpectsSensitiveRequestData
{
    public function getPropRequestKeyDefaultName(): string;
    public function setData(array $data): self;
    public function getData(): array;
}
