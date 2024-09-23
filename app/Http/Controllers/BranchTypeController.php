<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\BranchType;
use App\Traits\BranchParent;
use App\Traits\ParentTraitCompanyWise;
use http\Exception\BadConversionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Throwable;

class BranchTypeController extends Controller
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
     */
    public function index()
    {
        try {
            $branches = $this->getBranch()->orderBY('branch_name','asc')->get();
            $branchTypeAll = $this->getBranchType()->orderBY('code','asc')->get();
            return view('back-end.branch.list',compact('branches','branchTypeAll'))->render();
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage());
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
            $companies = $this->getCompany()->get();
            $branchTypeAll = $this->getBranchType()->orderBY('code','asc')->get();
            return view('back-end.branch.add-type',compact('branchTypeAll','companies'))->render();
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
        //
        try {
            $request->validate([
                'company' => ['required','string','exists:company_infos,id'],
                'branch_type_title' =>  ['required','string',Rule::unique('branch_types','title')->where(function ($query) use($request){return $query->where('company_id',$request->post('company'));})],
                'branch_type_code' =>  ['required','string', Rule::unique('branch_types','code')->where(function ($query) use($request) {return $query->where('company_id',$request->post('company'));})],
                'branch_type_status' =>  ['required','string'],
                'remarks' =>  ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            if ($branch_type_status)
                $status = 1;
            else
                $status = 0;
            $this->getBranchType()->create([
                'company_id'=>$company,'status'=>$status,'title'=>$branch_type_title,'code'=>$branch_type_code,'remarks'=>$remarks,'created_by'=> $this->user->id
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


    public function edit(Request $request, $id)
    {
        try {
            if ($request->isMethod('put'))
            {
                return $this->update($request,$id);
            }
            $branchType = $this->getBranchType()->where('id',Crypt::decryptString($id))->first();
            $branchTypeAll = $this->getBranchType()->orderBY('code','asc')->get();
            return view('back-end.branch.branch_type_edit',compact('branchTypeAll','branchType'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchType  $branchType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,$id)
    {
        try {
            $request->validate([

                'company' => ['required','string','exists:company_infos,id'],
                'branch_type_title' =>  ['required','string',Rule::unique('branch_types','title')->where(function ($query) use($request){return $query->where('company_id',$request->post('company'));})->ignore(Crypt::decryptString($id))],
                'branch_type_code' =>  ['required','string', Rule::unique('branch_types','code')->where(function ($query) use($request) {return $query->where('company_id',$request->post('company'));})->ignore(Crypt::decryptString($id))],
                'branch_type_status' =>  ['required','string'],
                'remarks' =>  ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            $typeID = Crypt::decryptString($id);
            if ($branch_type_status)
                $status = 1;
            else
                $status = 0;
            $this->getBranchType()->where('id',$typeID)->update([
//                'company' => $company,
                'status'=>$status,
                'title'=>$branch_type_title,
                'code'=>$branch_type_code,
                'remarks'=>$remarks,
                'updated_by'=> Auth::user()->id
            ]);
            return back()->with('success','Data update successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy(Request $request)
    {
        try {
            extract($request->post());
            $branchTypeChild = $this->getBranchType()->where('id',Crypt::decryptString($id))->first();
            if(count($branchTypeChild->getBranches))
            {
                return back()->with('warning','Deletion not possible! A relationship exists.');
            }
            $this->getBranchType()->where('id',Crypt::decryptString($id))->delete();
            return redirect()->route('branch.list')->with('success','Data delete successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
