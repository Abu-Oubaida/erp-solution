<?php

namespace App\Rules;

use App\Models\Role;
use Illuminate\Contracts\Validation\Rule;

class RoleStatusRule implements Rule
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
        return Role::where('id', $value)
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
        return 'The selected user role is invalid or inactive.';
    }
}
