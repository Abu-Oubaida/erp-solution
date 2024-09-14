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
            $roles = $this->getRole()->get();
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
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }else{
                $companies = $this->getCompany()->get();
                $roles = $this->getRole()->get();
                return view('back-end.role.add',compact('roles','companies'))->render();
            }

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $id = Crypt::decryptString($id);
            if ($request->isMethod('post'))
            {
                return $this->update($request,$id);
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    private function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255',Rule::unique('roles','name')->where(function ($query) use ($request){$query->where('company_id',$request->company);})],
                'display_name' => ['required', 'string', 'max:255',Rule::unique('roles','display_name')->where(function ($query) use ($request){$query->where('company_id',$request->company);})],
                'company' => ['required', 'string', 'max:255','exists:companies,id'],
                'description' => ['nullable','sometimes','string','max:255'],
            ]);
            extract($request->post());
            $this->getRole()->create([
                'company_id'=>$company,
                'name'=>$name,
                'display_name'=>$display_name,
                'description'=>$description,
                'created_by'=>$this->user->id,
                'created_at'=>date(now())
            ]);
            return back()->with('success','Role added successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    private function update(Request $request,$id)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255',Rule::unique('roles','name')->where(function ($query) use ($request){$query->where('company_id',$request->company);})->ignore($id,'id')],
                'display_name' => ['required', 'string', 'max:255',Rule::unique('roles','display_name')->where(function ($query) use ($request){$query->where('company_id',$request->company);})->ignore($id,'id')],
                'company' => ['required', 'string', 'max:255','exists:companies,id'],
                'description' => ['nullable','sometimes','string','max:255'],
            ]);
            extract($request->post());
            $this->getRole()->where('id',$id)->update([
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
}
