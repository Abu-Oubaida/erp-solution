<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\BranchType;
use App\Traits\BranchParent;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        try {

            $branches = $this->getBranch()->orderBY('branch_name','asc')->get();
            $branchTypeAll = $this->getBranchType()->orderBY('code','asc')->get();
            return view('back-end.branch.list',compact('branches','branchTypeAll'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
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
            $branches = $this->getBranch()->orderBY('branch_name','asc')->get();
            $branchTypeAll = $this->getBranchType()->orderBY('code','asc')->get();
            $branchTypeActive = $this->getBranchType()->where('status',1)->orderBY('code','asc')->get();
            return view('back-end.branch.add',compact('branchTypeActive','branchTypeAll','branches'))->render();
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
                'address'   => ['sometimes','nullable','string'],
                'remarks'   => ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            if ($branch_status)
                $status = 1;
            else
                $status = 0;
            $this->getBranch()->create([
                'company_id' => $this->user->company_id,
                'branch_name'   =>  $branch_name,
                'branch_type'   =>  $branch_type,
                'status'   =>  $status,
                'address'   =>  $address,
                'remarks'   =>  $remarks,
                'created_by'   =>  $this->user->id,
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
     */
    public function edit(Request $request,$id)
    {
        try {
            if ($request->isMethod('put'))
            {
                return $this->update($request,$id);
            }
            $id = Crypt::decryptString($id);
            $branch = $this->getBranch()->where('id',$id)->first();
            $branchTypeActive = $this->getBranchType()->where('status',1)->orderBY('code','asc')->get();
            $branches = $this->getBranch()->orderBY('branch_name','asc')->get();
            return view('back-end/branch/edit',compact('branch','branchTypeActive','branches'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $request->validate([
                'branch_name'   => ['required','string', Rule::unique('branches', 'branch_name')->ignore(Crypt::decryptString($id))],
                'branch_type'   => ['required','string','exists:branch_types,id'],
                'branch_status'   => ['required','string'],
                'address'   => ['sometimes','nullable','string'],
                'remarks'   => ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            $branchID = Crypt::decryptString($id);
            if ($branch_status)
                $status = 1;
            else
                $status = 0;
            $this->getBranch()->where('id',$branchID)->update([
                'branch_name'   =>  $branch_name,
                'branch_type'   =>  $branch_type,
                'status'   =>  $status,
                'address'   =>  $address,
                'remarks'   =>  $remarks,
                'updated_by'   =>  $this->user->id,
            ]);
            return back()->with('success','Data update successfully!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        try {
            if (request()->isMethod('delete'))
            {
                extract($request->post());
                $id = Crypt::decryptString($id);
                $this->getBranch()->where('id',$id)->delete();
                return back()->with('success','Data deleted successfully!');
            }
            return back()->with('error','Requested method not allowed!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
