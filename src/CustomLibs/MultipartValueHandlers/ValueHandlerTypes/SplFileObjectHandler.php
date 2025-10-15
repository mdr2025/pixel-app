<?php 

namespace PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes;


use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;
use SplFileObject;

class SplFileObjectHandler implements MultipartValueHandler
{
    public function supports(mixed $value): bool
    {
        return $value instanceof SplFileObject;
    }

    public function handle(string $name, mixed $value): array
    {
        /** @var SplFileObject $value */
        return [[
            'name' => $name,
            'contents' => $value->fread($value->getSize() ?: 1024 * 1024),
            'filename' => basename($value->getRealPath() ?: 'file.dat'),
        ]];
    }
}