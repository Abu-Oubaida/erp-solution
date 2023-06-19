<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\department;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(Request $request)
    {
        //
        if ($request->post())
        {
            $this->store($request);
        }
        $deplist= department::get();
        return view("back-end/department/add",compact('deplist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    private function store(Request $request)
    {
        //
        try {
            $request->validate([
                'dept_name'  => ['required', 'string', 'max:255','unique:'.department::class],
                'dept_code' => ['required', 'numeric', 'unique:'.department::class],
                'status' => ['required', 'string',],
                'remarks'=> ['sometime','nullable','string'],
            ]);
            extract($request->post());
            if (department::where("dept_name",$dept_name)->orWhere('dept_code',$dept_code)->where('status',1)->first())
            {
                return back()->with('error','Department name or code already exist in System');
            }
            department::create([
                'dept_name' => $dept_name,
                'dept_code' => $dept_code,
                'status'    =>  $status,
                'remarks'   =>  $remarks,
            ]);
            return back()->with('success','Data save successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(department $department)
    {
        //
    }
}
