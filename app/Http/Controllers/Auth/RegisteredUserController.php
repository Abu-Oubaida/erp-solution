<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\department;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        try {
            $depts = department::where('status',1)->get();
            $branches = branch::where('status',1)->get();
            return view('auth.register',compact('depts','branches'));
        }catch (\Throwable $exception)
        {
            return back();
        }

    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['required', 'numeric', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'dept'  => ['required', 'exists:departments,id'],
            'branch'  => ['required', 'exists:branches,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        extract($request->post());
        try {
            $deptInfo = department::where('id',$dept)->first();
            $branch = branch::where('id',$branch)->first();
            if ($branch->branch_type == 'head office') $header = 'H'; else $header = "P";
            $priviusUsers = User::where('status',1)->where('dept_id',$dept)->get();
            $priviusUserCount = count($priviusUsers);
//            dd($priviusUserCount >= 10 && $priviusUserCount < 100);
            if ($priviusUserCount < 10)
            {
                $priviusUserCount++;
                $empID = ($header.$deptInfo->dept_code."00").$priviusUserCount;
            }
            elseif ($priviusUserCount >= 10 && $priviusUserCount < 100)
            {
                $priviusUserCount++;
                $empID = ($header.$deptInfo->dept_code."0").$priviusUserCount;
            }
            else {
                $priviusUserCount++;
                $empID = $header.$deptInfo->dept_code.$priviusUserCount;
            }

            $user = User::create([
                'employee_id' => $empID,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'dept_id' => $deptInfo->id,
                'status' => 0,
                'branch_id' => $branch->id,
                'password' => Hash::make($request->password),
            ]);

            $user->attachRole('user');
            event(new Registered($user));
            return back()->with('success','Account create successfully');

//            Auth::login($user);

//            return redirect(RouteServiceProvider::HOME);
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }

    }
}
