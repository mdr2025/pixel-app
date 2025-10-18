<?php 

namespace PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes;

use Illuminate\Http\UploadedFile;
use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;

class UploadedFileValueHandler implements MultipartValueHandler
{
    public function supports(mixed $value): bool
    {
        return $value instanceof UploadedFile;
    }

    public function handle(string $name, mixed $value): array
    {
        /** @var UploadedFile $value */
        return [[
            'name' => $name,
            'contents' => fopen($value->getRealPath(), 'r'),
            'filename' => $value->getClientOriginalName(),
            'headers' => [
                'Content-Type' => $value->getMimeType(),
            ],
        ]];
    }
}