<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\department;
use App\Models\User;
use App\Rules\uniqueFixedAssetIDCheck;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return string
     */
    public function create(Request $request)
    {
        try {
            if ($request->post())
            {
                $this->store($request);
            }
            $companies = $this->getCompany()->get();
            $deplist= $this->getDepartment()->get();
            return view("back-end/department/add",compact('deplist','companies'))->render();
        }catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
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
                'dept_name'  => ['required', 'string', 'max:255',Rule::unique('departments')],
                'dept_code' => ['required', 'numeric', Rule::unique('departments')],
                'status' => ['required', 'string',],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'remarks'=> ['nullable','sometimes','string'],
            ]);
            extract($request->post());
            if ($company != $this->user->company_id || !$this->user->isSystemSuperAdmin())
            {
                return redirect(route('dashboard'))->with('error','Company not allowed');
            }
            if ($this->getDepartment()->where("dept_name",$dept_name)->orWhere('dept_code',$dept_code)->where('status',1)->first())
            {
                return back()->with('error','Department name or code already exist in System');
            }
            $this->getDepartment()->create([
                'company_id'=> $this->user->company_id,
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
     * @return string
     */
    public function edit(Request $request,$id)
    {
        try {
            $id = Crypt::decryptString($id);
            if ($request->isMethod('put'))
            {
                return $this->update($request,$id);
            }
            $companies = $this->getCompany()->get();
            $deplist= $this->getDepartment()->get();
            $department = $this->getDepartment()->find($id);
            return view("back-end/department/edit",compact('department','companies','deplist'))->render();
        }catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'dept_name'  => ['required', 'string', 'max:255',Rule::unique('departments')->ignore($id)],
                'dept_code' => ['required', 'numeric', Rule::unique('departments')->ignore($id)],
                'status' => ['required', 'string',],
                'remarks'=> ['nullable','string'],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
            ]);
            $this->getDepartment()->where("id",$id)->update([
                'dept_name' => $request->post('dept_name'),
                'dept_code' => $request->post('dept_code'),
                'status' => $request->post('status'),
                'remarks' => $request->post('remarks'),
                'company_id' => $request->post('company'),
                'updated_by' => $this->user->id,
                'updated_at' => now(),
            ]);
            return back()->with('success','Data update successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        try {
            if ($request->isMethod('delete'))
            {
                $request->validate(['id'=>['required','string',Rule::exists('departments','id')]]);
                extract($request->post());
                if (count($this->getDepartment()->where("id",$id)->first()->getUsers))
                {
                    return back()->with('warning','Deletion not possible! A relationship exists.');
                }
                $this->getDepartment()->where("id",$id)->delete();
                return back()->with('success','Data deleted successfully');
            }
            return back()->with('error','Requested Method Not Allowed');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
