<?php

namespace PixelApp\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterValueDependOnAnotherKeyValueRule implements Rule
{
    private array $values;
    private string $anotherKey;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $values , string $anotherKey)
    {
        $this->values = $values;
        $this->anotherKey = $anotherKey;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $values = $this->values;
        $anotherKey = $this->anotherKey;
        foreach ($values as $valueAnotherKey=>$array) {
            if (request()->input($anotherKey) === $valueAnotherKey) {
                return in_array($value, $array);
            }

        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected value is invalid for the current context.';
    }
}
