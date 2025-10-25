<?php

namespace PixelApp\Helpers;
 
use stdClass; 
use Illuminate\Http\JsonResponse; 

class ResponseHelpers
{
 
    //JsonResponse Handling Methods - start
    static public function getResponseData(JsonResponse $response , bool $assocArray = true): stdClass|array
    {
        return $response->getData($assocArray);
    }

    static public function IsResponseStatusSuccess(JsonResponse $response): bool
    {
        $status = static::getResponseStatus($response);
        return  static::getResponseStatus($response) 
                &&
                static::getResponseStatus($response) == "success";
    }

    static public function getResponseStatus(JsonResponse $response): ?string
    {
        return static::getResponseData($response , false)->status ?? null;
    }

    static public function getResponseMessages(JsonResponse $response): array
    {
        return static::getResponseData($response , false)->messages;
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
