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
            $permission = $this->permissions()->add_department;
            if ($request->post())
            {
                $this->store($request);
            }
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            $deplist = $this->getDepartment($permission)->get();
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
            $permission = $this->permissions()->add_department;
            $request->validate([
                'dept_name'  => ['required', 'string', 'max:255',Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request->post('company'));
                }),],
                'dept_code' => ['required', 'numeric', Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request->post('company'));
                })],
                'status' => ['required', 'string','between:0,1'],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'remarks'=> ['nullable','sometimes','string'],
            ]);
            extract($request->post());
            if ($company == $this->user->company_id || ($this->user->isSystemSuperAdmin()))
            {
                $this->getDepartment($permission)->create([
                    'company_id'=> $company,
                    'dept_name' => $dept_name,
                    'dept_code' => $dept_code,
                    'status'    =>  $status,
                    'remarks'   =>  $remarks,
                ]);
                return back()->with('success','Data save successfully');
            }
            return redirect(route('dashboard'))->with('error','Company not allowed');

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
            $permission = $this->permissions()->edit_department;
            $id = Crypt::decryptString($id);
            if ($request->isMethod('put'))
            {
                return $this->update($request,$id);
            }
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            $deplist= $this->getDepartment($permission)->get();
            $department = $this->getDepartment($permission)->find($id);
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
            $permission = $this->permissions()->edit_department;
            $request->validate([
                'dept_name'  => ['required', 'string', 'max:255',Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request->post('company'));
                })->ignore($id)],
                'dept_code' => ['required', 'numeric', Rule::unique('departments')->where(function ($query) use ($request) {
                    return $query->where('company_id', $request->post('company'));
                })->ignore($id)],
                'status' => ['required', 'string','between:0,1'],
                'remarks'=> ['nullable','string'],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
            ]);
            extract($request->post());
            if ($company == $this->user->company_id || ($this->user->isSystemSuperAdmin()))
            {
                $this->getDepartment($permission)->where("id",$id)->update([
                    'dept_name' => $dept_name,
                    'dept_code' => $dept_code,
                    'status' => $status,
                    'remarks' => $remarks,
                    'company_id' => $company,
                    'updated_by' => $this->user->id,
                    'updated_at' => now(),
                ]);
                return back()->with('success','Data update successfully');
            }
            return redirect(route('dashboard'))->with('error','Company not allowed');

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
            $permission = $this->permissions()->delete_department;
            if ($request->isMethod('delete'))
            {
                $request->validate(['id'=>['required','string',Rule::exists('departments','id')]]);
                extract($request->post());
                if (count($this->getDepartment($permission)->where("id",$id)->first()->getUsers))
                {
                    return back()->with('warning','Deletion not possible! A relationship exists.');
                }
                $this->getDepartment($permission)->where("id",$id)->delete();
                return redirect(route('add.department'))->with('success','Data deleted successfully');
            }
            return back()->with('error','Requested Method Not Allowed');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
