<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MobileSIMController extends Controller
{
    //
    public function index()
    {

    }

    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                //submit
            }else{
                return view('back-end/sim/add');
            }
        }catch (\Throwable $exception)
        {
            return back();
        }
    }
}
