<?php

namespace PixelApp\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
        return  is_string($value) && $this->isItValidPhone($value) && $this->checkMaxCharLength($value);
    }

    protected function getMaxChacrLength() : int
    {
        return 20;
    }
    protected function checkMaxCharLength(string $value) : bool
    {
        return strlen($value) <= $this->getMaxChacrLength() ;
    }

    protected function isItValidPhone($value) : bool
    {
        // Basic international phone validation
        return preg_match('/^\+?[0-9\s\-\(\)]{7,}$/', $value);
    }

    public static function create() : self
    {
        return new static();
    }

    public function message()
    {
        return 'The :attribute must be a valid phone number.';
    }
}