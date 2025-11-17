<?php

namespace PixelApp\Helpers;
 
use Illuminate\Support\MessageBag; 

class ValidationHelpers
{

    static public function getErrorsIndexedArray(MessageBag | array $bag): array
    {
        if($bag instanceof MessageBag)
        {
            $errorBagArray = $bag->toArray();
        }

        $array = [];
        foreach ($errorBagArray as $messages)
        {
            foreach ($messages as $message) {
                $array[] = $message;
            }
        }
        return $array;
    }
}
