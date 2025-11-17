<?php

namespace PixelApp\Rules;

use Illuminate\Contracts\Validation\Rule;

class NoCode implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        // Disallow patterns indicating code
        $patterns = [
            '/<\?php/',    // PHP tags
            '/<script>/',  // JavaScript tags
            '/<\/script>/',
            '/@php/',      // Blade PHP directive
            '/<\?/',       // Short PHP tags
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'The :attribute field contains invalid content.';
    }
}
