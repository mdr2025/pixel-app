<?php

namespace PixelApp\PixelMacroableExtenders;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class PixelCarbonExtender extends PixelMacroableExtender
{
    public function extendMacroable() : void
    {
        $this->defineCarbonParseOrNowMacro();
    }
    
    protected function defineCarbonParseOrNowMacro() : void
    { 
        Carbon::macro('parseOrNow', function ($date = ""): Carbon {
            try {
                return $date ? Carbon::parse($date) : Carbon::now();
            } catch (InvalidFormatException) {
                return Carbon::now();
            }
        });
    }
}
