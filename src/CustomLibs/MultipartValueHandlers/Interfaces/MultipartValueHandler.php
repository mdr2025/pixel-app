<?php

namespace PixelApp\CustomLibs\MultipartValueHandlers\Interfaces;


interface MultipartValueHandler
{
    public function supports(mixed $value): bool;

    /** @return array{name: string, contents: mixed, filename?: string}[] */
    public function handle(string $name, mixed $value): array;
}