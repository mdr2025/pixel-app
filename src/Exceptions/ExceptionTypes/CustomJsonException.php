<?php
namespace PixelApp\Exceptions\ExceptionTypes;

use Throwable;

class CustomJsonException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(json_encode(['error' => $message]), 405, $previous);
    }
}
