<?php

namespace PixelApp\CustomLibs\MultipartValueHandlers\MultipartArrayConverters;

use PixelApp\CustomLibs\MultipartValueHandlers\Interfaces\MultipartValueHandler;
use PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes\ArrayValueHandler;
use PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes\FilePathValueHandler;
use PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes\ScalarValueHandler;
use PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes\SplFileObjectHandler;
use PixelApp\CustomLibs\MultipartValueHandlers\ValueHandlerTypes\StreamingValueHandler;

class MultipartArrayConverter
{
    /** @var MultipartValueHandler[] */
    private array $handlers;

    public function __construct(?array $handlers = null)
    {
        $this->setHandlers($handlers);
    }

    public function setHandlers(?array $handlers = null)
    {
        if(!$handlers)
        {
            $handlers = $this->getDefaultMultipartValueHandlers();
        }else
        {
            $handlers = $this->filterMultipartValueHandlersArray($handlers);
            //no need to inject our MultipartValueHandler types because the user is only want 
            //to handle data by his handler types
        }        

        $this->handlers = $handlers ;
    }

    protected function filterMultipartValueHandlersArray(array $handlers) : array
    {
        return  array_filter($handlers , function($handler)
                {
                    return $handler instanceof MultipartValueHandler;
                });
    }

    protected function getDefaultMultipartValueHandlers() : array
    {
        return [
                   FilePathValueHandler::class  => new FilePathValueHandler(),
                   ScalarValueHandler::class    => new ScalarValueHandler(),
                   ArrayValueHandler::class     => new ArrayValueHandler($this),
                   SplFileObjectHandler::class  => new SplFileObjectHandler(),
                   StreamingValueHandler::class => new StreamingValueHandler()
        ];
    }

    public function convert(array $data, string $prefix = ''): array
    {
        $multipart = [];

        foreach ($data as $key => $value) {
            $fieldName = $prefix ? "{$prefix}[{$key}]" : $key;

            foreach ($this->handlers as $handler) {
                if ($handler->supports($value)) {
                    $multipart = array_merge($multipart, $handler->handle($fieldName, $value));
                    break;
                }
            }
        }

        return $multipart;
    }
}