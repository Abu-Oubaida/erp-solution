<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class CustomSeparatorRule implements Rule
{
    use ValidatesAttributes;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function validate($attribute, $value, $parameters, $validator)
    {
        $separator = $parameters[0] ?? '_'; // Default separator is '-'

        $pattern = "/^[a-zA-Z0-9]+{$separator}[a-zA-Z0-9]+{$separator}[a-zA-Z0-9]+$/";

        return (bool) preg_match($pattern, $value);
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
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Without underscore "_" all separator are invalid!';
    }
}
