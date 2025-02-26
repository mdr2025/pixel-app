<?php

namespace PixelApp\Helpers;
 
use Illuminate\Support\MessageBag; 

class ValidationHelpers
{

    static public function getErrorsIndexedArray(MessageBag $bag): array
    {
        $errorBagArray = $bag->toArray();
        $array = [];
        foreach ($errorBagArray as $messages) {
            foreach ($messages as $message) {
                $array[] = $message;
            }
        }
        return $array;
    }
}
