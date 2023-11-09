<?php

namespace App\Rules;

use App\Models\branch;
use Illuminate\Contracts\Validation\Rule;

class BranchStatusRule implements Rule
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
        //
        return branch::where('id', $value)
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
        return 'The selected branch is invalid or inactive.';
    }
}
