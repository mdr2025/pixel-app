<?php

namespace PixelApp\PixelMacroableExtenders;

use Illuminate\Support\Str;

class PixelStrExtender extends PixelMacroableExtender
{
    public function extendMacroable() : void
    {
        $this->defineStrSnakeToTitleMacro();
        $this->defineStrHumanTextMacro();
        $this->defineStrEqualIgnoreCaseMacro();
    }

    protected function defineStrSnakeToTitleMacro() : void
    {
        Str::macro('snakeToTitle', function ($value) {
            return ucwords(str_replace('_', ' ', $value));
        });
    }
    protected function defineStrHumanTextMacro() : void
    {
        Str::macro('humanText', function ($value) {
            $text = preg_replace("/[^a-zA-Z0-9]+/", " ", $value);
            return Str::title($text);
        });
    }

    protected function defineStrEqualIgnoreCaseMacro() : void
    {
        Str::macro('equalIgnoreCase', function (string $string, string $comparison, $encoding = 'UTF-8'): bool {
            // Normalize Unicode strings
            //$value1 = Normalizer::normalize(string: $string, Normalizer::FORM_C);
            //$value2 = Normalizer::normalize($comparison, Normalizer::FORM_C);
        
            // Convert to lowercase using the specified encoding
            $string = mb_strtolower($string, $encoding);
            $comparison = mb_strtolower($comparison, $encoding);
        
            return ($string === $comparison);
        });
    }
} 