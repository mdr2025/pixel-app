<?php

namespace PixelApp\Exceptions\ExceptionTypes;



use Exception;
use Illuminate\Support\Facades\Response;

//use Illuminate\Auth\Access\AuthorizationException;


class JsonException extends Exception
{
    protected $code = 500;
    public function render()
    {
        return Response::error($this->getMessage(), $this->getCode());//intval($this->getCode()) ?? 
    }

     public function report()
    {
        return Response::error($this->getMessage(), $this->getCode());
    } 
}
