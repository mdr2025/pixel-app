<?php

namespace PixelApp\Helpers;
 
use stdClass; 
use Illuminate\Http\JsonResponse; 

class ResponseHelpers
{
 
    //JsonResponse Handling Methods - start
    static public function getResponseData(JsonResponse $response , bool $assocArray = false): stdClass|array
    {
        return $response->getData($assocArray);
    }
    static public function IsResponseStatusSuccess(JsonResponse $response): bool
    {
        return static::getResponseData($response)->status == "success";
    }

    static public function getResponseStatus(JsonResponse $response): string
    {
        return static::getResponseData($response)->status;
    }

    static public function getResponseMessages(JsonResponse $response): array
    {
        return static::getResponseData($response)->messages;
    }

    public static function isArrayOfStrings($array)
    {
        return count($array) === count(array_filter($array, 'is_string'));
    }

    public static function isArrayOfArrays($array)
    {
        return count($array) === count(array_filter($array, 'is_array'));
    }
 
}
