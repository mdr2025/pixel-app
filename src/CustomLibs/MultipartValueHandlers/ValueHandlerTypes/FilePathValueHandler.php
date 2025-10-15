<?php 

namespace PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes;

use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;

class FilePathValueHandler  implements MultipartValueHandler
{
    public function supports(mixed $value): bool
    {
        return is_string($value) && file_exists($value);
    }

    public function handle(string $name, mixed $value): array
    {
        return [[
            'name' => $name,
            'contents' => fopen($value, 'r'),
            'filename' => basename($value),
        ]];
    }
}