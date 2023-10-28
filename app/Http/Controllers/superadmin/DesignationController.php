<?php

namespace App\Http\Controllers\superadmin;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignationController extends Controller
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
                return $this->store($request);
            }
            $designations = Designation::all();
            return view('back-end/designation/add',compact('designations'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function store(Request $request)
    {
        try {
            $request->validate([
                'title' =>  ['required','string','unique:designations,title',],
                'priority' =>  ['required','numeric','unique:designations,priority',],
                'status' =>  ['required','numeric',],
                'remarks' =>  ['sometimes','nullable','string',],
            ]);
            extract($request->post());
            $user = Auth::user();
            ($status == 1)?$status_new = 1:$status_new = 0;
            Designation::create([
                'title'     =>  $title,
                'priority'  =>  $priority,
                'status'    =>  $status_new,
                'remarks'   =>  $remarks,
                'created_by'=>  $user->id,
            ]);
            return back()->with('success','Data save successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
