<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index()
    {
        try {
            $auth = Auth::user();
            $user = User::leftJoin('departments as dept','dept.id','users.dept_id')->leftJoin('role_user as ur','ur.user_id','users.id')->leftJoin('roles as r','r.id','ur.role_id')->where('users.id',$auth->id)->select('dept.dept_name','r.display_name','r.id as role_id','users.*')->first();
            return view('back-end.index',compact('user'));
        }catch (\Throwable $exception)
        {
            return $exception->getMessage();
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function ChangePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);
            if(!Hash::check($request->old_password, Auth::user()->password)){
                return back()->with("error", "Old Password Doesn't match!");
            }
            User::whereId(Auth::user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with("success", "Password changed successfully!");
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
