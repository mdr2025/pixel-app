<?php

namespace PixelApp\Rules;

use Illuminate\Contracts\Validation\Rule;

class NotMainBranchName implements Rule
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
    public function passes($attribute, $value): bool
    {
        $normalized = strtolower($value);

        return !preg_match('/^main[\s_-]*branch$/i', $normalized);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The branch name cannot be the main branch name.';
    }
}
