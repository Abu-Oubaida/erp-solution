<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\department;
use Illuminate\Http\Request;

class SalesInterfaceController extends Controller
{
    //
    public function index()
    {
        return view('back-end.sales.dashboard');
    }
    public function addLead(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->storeLead($request);
            }
            $depts = department::where('status',1)->get();
            $branches = branch::where('status',1)->get();
//            dd($depts);
            return view('back-end/sales/add-lead',compact('depts','branches'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function storeLead(Request $request)
    {
        try {
            return true;
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function leadList()
    {
        try {
            return true;
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
