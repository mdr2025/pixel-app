<?php 

namespace PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes;

use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;

class ScalarValueHandler implements MultipartValueHandler
{
    public function supports(mixed $value): bool
    {
        return !is_array($value);
    }

    public function handle(string $name, mixed $value): array
    {
        return [[
            'name' => $name,
            'contents' => $value,
        ]];
    }
}