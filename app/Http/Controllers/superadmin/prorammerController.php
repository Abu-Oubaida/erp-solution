<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Request;

class prorammerController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $this->store($request);
            }
            $permissions = Permission::with('childPermission')->where('parent_id',null)->get();
            return view('back-end/programmer/add',compact("permissions"));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    private function store(Request $request)
    {
        try {

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
