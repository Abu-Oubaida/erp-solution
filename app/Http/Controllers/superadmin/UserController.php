<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\department;
use App\Models\Role;
use http\Exception\BadConversionException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function create()
    {
        try {
            $depts = department::where('status',1)->get();
            $branches = branch::where('status',1)->get();
            $roles = Role::get();
            return view('back-end.user.add',compact('depts','branches','roles'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }


    }
}
