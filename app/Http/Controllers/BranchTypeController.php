<?php

namespace App\Http\Controllers;

use App\Models\BranchType;
use http\Exception\BadConversionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchTypeController extends Controller
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
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        //
        try {
            $request->validate([
                'branch_type_title' =>  ['required','string','unique:branch_types,title'],
                'branch_type_code' =>  ['required','string','unique:branch_types,code'],
                'branch_type_status' =>  ['required','string'],
                'remarks' =>  ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            if ($branch_type_status)
                $status = 1;
            else
                $status = 0;
            BranchType::create([
                'status'=>$status,'title'=>$branch_type_title,'code'=>$branch_type_code,'remarks'=>$remarks,'created_by'=> Auth::user()->id
            ]);
            return back()->with('success','Data added successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchType  $branchType
     * @return \Illuminate\Http\Response
     */
    public function show(BranchType $branchType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchType  $branchType
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchType $branchType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchType  $branchType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchType $branchType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchType  $branchType
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchType $branchType)
    {
        //
    }
}
