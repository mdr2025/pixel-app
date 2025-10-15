<?php 

namespace PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes;

use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;
use Psr\Http\Message\StreamInterface;
 
class StreamingValueHandler implements MultipartValueHandler
{
    public function supports(mixed $value): bool
    {
        return $value instanceof StreamInterface;
    }

    public function handle(string $name, mixed $value): array
    {
        /** @var StreamInterface $value */
        $meta = $value->getMetadata();

        $filename = $meta['uri'] ?? 'stream.bin';
        if (is_string($filename) && str_starts_with($filename, 'php://')) {
            $filename = 'stream.bin'; // default name
        }

        return [[
            'name' => $name,
            'contents' => $value,
            'filename' => basename($filename),
        ]];
    }
}