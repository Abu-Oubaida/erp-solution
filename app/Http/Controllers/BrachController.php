<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\BranchType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrachController extends Controller
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

     */
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }
            $branches = branch::with(['branchType','createdBy','updatedBy'])->orderBY('branch_name','asc')->get();
            $branchTypeAll = BranchType::with(['createdBy','updatedBy'])->orderBY('code','asc')->get();
            $branchTypeActive = BranchType::where('status',1)->orderBY('code','asc')->get();
            return view('back-end/branch/add',compact('branchTypeActive','branchTypeAll','branches'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'branch_name'   => ['required','string','unique:branches,branch_name'],
                'branch_type'   => ['required','string','exists:branch_types,id'],
                'branch_status'   => ['required','string'],
                'remarks'   => ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            if ($branch_status)
                $status = 1;
            else
                $status = 0;
            branch::create([
                'branch_name'   =>  $branch_name,
                'branch_type'   =>  $branch_type,
                'status'   =>  $status,
                'remarks'   =>  $remarks,
                'created_by'   =>  Auth::user()->id,
            ]);
            return back()->with('success','Data added successfully!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
