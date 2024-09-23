<?php

namespace App\Http\Requests\Auth;

use App\Models\company_info;
use App\Models\User;
use App\Models\UserCompanyPermission;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'company_id'=> ['required','string','exists:company_infos,id']
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
//        if (! (Auth::attempt(['email'=>$this->email,'password'=>$this->password,'status'=>1,'company_id'=>$this->company_id], $this->boolean('remember')) || Auth::attempt(['phone'=>$this->email,'password'=>$this->password,'status'=>1,'company_id'=>$this->company_id], $this->boolean('remember'))))
        if (! (Auth::attempt(['email'=>$this->email,'password'=>$this->password,'status'=>1], $this->boolean('remember')) || Auth::attempt(['phone'=>$this->email,'password'=>$this->password,'status'=>1], $this->boolean('remember'))))
        {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
        // Step 2: Check the company_id in user_company_permissions
        $user = Auth::user(); // Get authenticated user
        if ($user->isSystemSuperAdmin())
        {
            $companyPermission = company_info::where('id',$this->company_id)->first();
        }
        else {
            $userComPerIDs = [];
            $userCompanyPermissions = UserCompanyPermission::where('user_id',$user->id)->get();
            if (count($userCompanyPermissions) > 0) {
                $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
            }
            $userComPerIDs[] = (integer)$user->company;
            $companyPermission = company_info::whereIn('id',$userComPerIDs)->where('id',$this->company_id)->first();


//            $companyPermission = UserCompanyPermission::where('user_id', $user->id)
//                ->where('company_id', $this->company_id)
//                ->first();
        }
        // If company_id is valid, store it in the session
        if ($companyPermission) {
            // Step 3: Set the company_id in the session
            session(['company_id' => $this->company_id]);

            // Redirect the user to the intended page
//            return redirect()->intended('dashboard');
        } else {
            // If company_id is not valid, log the user out and return an error
            Auth::logout();

            throw ValidationException::withMessages([
                'company_id' => 'Invalid company selection.',
            ]);
        }
//        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
//            RateLimiter::hit($this->throttleKey());
//
//            throw ValidationException::withMessages([
//                'email' => trans('auth.failed'),
//            ]);
//        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth_old.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
