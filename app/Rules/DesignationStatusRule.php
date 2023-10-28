<?php

namespace App\Rules;

use App\Models\Designation;
use Illuminate\Contracts\Validation\Rule;

class DesignationStatusRule implements Rule
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
        // Check if a record with the provided ID and status = 1 exists in the designations table.
        return Designation::where('id', $value)
            ->where('status', 1)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected designation is invalid or inactive.';
    }
}
