<?php

namespace App\Rules;

use App\Models\Account_voucher;
use App\Models\department;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Crypt;

class AccountVoucherInfoStatusRule implements Rule
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
        return Account_voucher::where('id', Crypt::decryptString($value))
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected voucher is invalid or inactive.';
    }
}
