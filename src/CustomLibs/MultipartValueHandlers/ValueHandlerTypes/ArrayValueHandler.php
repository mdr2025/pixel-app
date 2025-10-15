<?php 

namespace PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes;

use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;
use PixelApp\CustomLibs\MultipartValueHandlers\MultipartArrayConverters\MultipartArrayConverter;

class ArrayValueHandler implements MultipartValueHandler
{
    public function __construct(private MultipartArrayConverter $converter) {}

    public function supports(mixed $value): bool
    {
        return is_array($value);
    }

    public function handle(string $name, mixed $value): array
    {
        return $this->converter->convert($value, $name);
    }
}