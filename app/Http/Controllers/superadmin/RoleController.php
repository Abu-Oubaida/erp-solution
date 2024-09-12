<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    //
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }else{
                $companies = $this->getCompany()->get();
                $roles = $this->getRole()->get();
                return view('back-end.role.add',compact('roles','companies'))->render();
            }

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
