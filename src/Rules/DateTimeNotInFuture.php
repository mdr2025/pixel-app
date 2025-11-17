<?php

namespace PixelApp\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateTimeNotInFuture implements Rule
{
    /**
     * @var $date
     */
    protected $date;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
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
        try {
            // Combine date and time to create a complete datetime
            $dateTime = Carbon::createFromFormat('Y-m-d h:i A', "{$this->date} {$value}");

            // Check if the combined datetime is not in the future
            return $dateTime->lessThanOrEqualTo(Carbon::now());
        } catch (\Exception $e) {
            // If parsing fails, return false (validation fails)
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The selected date and time cannot be in the future.';
    }
}
