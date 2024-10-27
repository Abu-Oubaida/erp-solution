<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    public function index()
    {
        try {
            $permission = $this->permissions()->role_management;
            $roles = $this->getRole($permission)->get();
            return view('back-end.role.index', compact('roles'));
        }catch (\Throwable $exception)
        {
            return redirect(route('dashboard'))->with('error', $exception->getMessage());
        }
    }
    //
    public function create(Request $request)
    {
        try {
            $permission = $this->permissions()->add_role;
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }else{
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                $roles = $this->getRole($permission)->get();
                return view('back-end.role.add',compact('roles','companies'))->render();
            }

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $permission = $this->permissions()->edit_role;
            $id = Crypt::decryptString($id);
            if ($request->isMethod('put'))
            {
                return $this->update($request,$id);
            }
            $roles = $this->getRole($permission)->get();
            $role = $this->getRole($permission)->where('id',$id)->first();
            $companies = $this->getCompany()->get();
            return view('back-end.role.edit',compact('role','roles','companies',))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    private function store(Request $request)
    {
        try {
            $permission = $this->permissions()->add_role;
            $request->validate([
                'name' => ['required', 'string', 'max:255','regex:/^[a-z0-9_]+$/',Rule::unique('roles','name')->where(function ($query) use ($request){$query->where('company_id',$request->post('company'));})],
                'display_name' => ['required', 'string', 'max:255',Rule::unique('roles','display_name')->where(function ($query) use ($request){$query->where('company_id',$request->post('company'));})],
                'company' => ['required', 'string', 'max:255','exists:company_infos,id'],
                'description' => ['nullable','sometimes','string','max:255'],
            ]);
            extract($request->post());
            if (@$name == 'systemsuperadmin' && $this->user->roles->first() !== 'systemsuperadmin')
            {
                return back()->with('error','Role name already exists');
            }
            if (@$name == 'superadmin' && ($this->user->roles->first() !== 'systemsuperadmin' || $this->user->roles->first() !== 'superadmin'))
            {
                return back()->with('error','Role name already exists');
            }
            $this->getRole($permission)->create([
                'company_id'=>$company,
                'name'=>$name,
                'display_name'=>$display_name,
                'description'=>$description,
                'created_by'=>$this->user->id,
                'created_at'=>date(now()),
                'updated_at'=>null,
            ]);
            return back()->with('success','Role added successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    private function update(Request $request,$id)
    {
        try {
            $permission = $this->permissions()->edit_role;
            $request->validate([
                'name' => ['required', 'string', 'max:255','regex:/^[a-z0-9_]+$/', Rule::unique('roles','name')->where(function ($query) use ($request){$query->where('company_id',$request->post('company'));})->ignore($id,'id')],
                'display_name' => ['required', 'string', 'max:255',Rule::unique('roles','display_name')->where(function ($query) use ($request){$query->where('company_id',$request->post('company'));})->ignore($id,'id')],
                'company' => ['required', 'string', 'max:255','exists:company_infos,id'],
                'description' => ['nullable','sometimes','string','max:255'],
            ]);
            extract($request->post());
            $this->getRole($permission)->where('id',$id)->update([
                'company_id'=>$company,
                'name'=>$name,
                'display_name'=>$display_name,
                'description'=>$description,
                'updated_by'=>$this->user->id,
                'updated_at'=>date(now())
            ]);
            return back()->with('success','Role update successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function destroy(Request $request)
    {
        try {
            $permission = $this->permissions()->delete_role;
            $request->validate([
                'id'    =>  ['string','required',Rule::exists('roles','id')],
            ]);
            extract($request->post());
            if (count($this->getRole($permission)->where('id',$id)->first()->getUsers))
            {
                return back()->with('warning','This data has relation between another table. Data delete not possible!');
            }
            $this->getRole($permission)->where('id',$id)->delete();
            return redirect(route('role.list'))->with('success','Role deleted successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
