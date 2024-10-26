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
            $branches = $this->getBranch($this->permissions()->list_branch)->orderBY('branch_name','asc')->get();
            $branchTypeAll = $this->getBranchType($this->permissions()->list_branch_type)->orderBY('code','asc')->get();
            return view('back-end.branch.list',compact('branches','branchTypeAll'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function changeCompany(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $branchTypes = $this->getBranchType($this->permissions()->branch)->where('company_id',$company_id)->get();
                return response()->json([
                    'status' => 'success',
                    'data'=>['branchTypes'=>$branchTypes],
                    'message' => 'Request processed successfully.'
                ]);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request not allowed'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message'=>$exception->getMessage()
            ]);
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
            $companies = $this->getCompanyModulePermissionWise($this->permissions()->add_branch)->get();
            $branches = $this->getBranch($this->permissions()->add_branch)->orderBY('branch_name','asc')->get();
            $branchTypeAll = $this->getBranchType($this->permissions()->add_branch)->orderBY('code','asc')->get();
            $branchTypeActive = $this->getBranchType($this->permissions()->add_branch)->where('status',1)->orderBY('code','asc')->get();
            return view('back-end.branch.add',compact('branchTypeActive','branchTypeAll','branches','companies'))->render();
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
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'branch_name'   => ['required','string',Rule::unique('branches','branch_name')->where(function ($query) use($request){return $query->where('company_id',$request->post('company'));})],
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
            $this->getBranch($this->permissions()->add_branch)->create([
                'company_id' => $company,
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
            $companies = $this->getCompanyModulePermissionWise($this->permissions()->edit_branch)->get();
            //need to work if permission dose not exit edit not possible.


            $branch = $this->getBranch($this->permissions()->edit_branch)->where('id',$id)->first();
            $branchTypeActive = $this->getBranchType($this->permissions()->edit_branch)->where('company_id',$branch->company_id)->orderBY('code','asc')->get();
            $branches = $this->getBranch($this->permissions()->edit_branch)->orderBY('branch_name','asc')->get();
            return view('back-end/branch/edit',compact('branch','branchTypeActive','branches','companies'))->render();
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
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'branch_name'   => ['required','string', Rule::unique('branches', 'branch_name')->where(function ($query) use ($request) {return $query->where('company_id',$request->post('company'));})->ignore(Crypt::decryptString($id))],
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
            $this->getBranch($this->permissions()->edit_branch)->where('id',$branchID)->update([
                'company_id' => $company,
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
                if (count($this->getBranch($this->permissions()->delete_branch)->where('id',$id)->first()->getUsers())>0)
                {
                    return back()->with('warning','Deletion not possible! A relationship exists.');
                }
                $this->getBranch($this->permissions()->delete_branch)->where('id',$id)->delete();
                return back()->with('success','Data deleted successfully!');
            }
            return back()->with('error','Requested method not allowed!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
