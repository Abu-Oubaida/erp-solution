<?php

namespace App\Http\Controllers\superadmin;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class DesignationController extends Controller
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

    }
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }
//            $designations = $this->getDesignation()->orderBY('priority','asc')->get();
            $companies = $this->getCompany()->get();
            return view('back-end.designation.add',compact('companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function edit(Request $request,$designationID)
    {
        try {
            $id = Crypt::decryptString($designationID);
            if ($request->isMethod('put'))
            {
                return $this->update($request,$id);
            }
            $designations = $this->getDesignation()->orderBY('priority','asc')->get();
            $companies = $this->getCompany()->get();
            $designation = $this->getDesignation()->find($id);
            return view('back-end.designation.edit',compact('designations','companies','designation'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function show()
    {
        try {
            $designations = $this->getDesignation()->orderBY('priority','asc')->get();
            return view('back-end.designation.list',compact('designations'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    private function store(Request $request)
    {
        try {
            $request->validate([
                'title' =>  ['required','string',Rule::unique('designations','title')->where(function ($query) use ($request){return $query->where('company_id',$request->post('company'));})],
                'priority' =>  ['required','numeric',Rule::unique('designations','priority')->where(function ($query) use ($request){return $query->where('company_id',$request->post('company'));})],
                'status' =>  ['required','numeric','between:0,1'],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'remarks' =>  ['sometimes','nullable','string',],
            ]);
            extract($request->post());
            if ($company == $this->user->company_id || ($this->user->isSystemSuperAdmin()))
            {
                $this->getDesignation()->create([
                    'company_id' => $company,
                    'title'     =>  $title,
                    'priority'  =>  $priority,
                    'status'    =>  $status,
                    'remarks'   =>  $remarks,
                    'created_by'=>  $this->user->id,
                ]);
                return back()->with('success','Data save successfully');
            }
            return redirect(route('dashboard'))->with('error','Company not allowed');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    private function update(Request $request, $designationID)
    {
        try {
            $request->validate([
                'title' =>  ['required','string',Rule::unique('designations','title')->where(function ($query) use ($request){return $query->where('company_id',$request->post('company'));})->ignore($designationID,'id')],
                'priority' =>  ['required','numeric',Rule::unique('designations','priority')->where(function ($query) use ($request){return $query->where('company_id',$request->post('company'));})->ignore($designationID,'id')],
                'status' =>  ['required','numeric','between:0,1'],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'remarks' =>  ['sometimes','nullable','string',],
            ]);
            extract($request->post());
            if ($company == $this->user->company_id || ($this->user->isSystemSuperAdmin()))
            {
                if ($company != $this->user->company_id)
                {
                    $designation = $this->getDesignation()->find($designationID);
                    if(count($designation->getUsers))
                    {
                        return back()->with('warning','Company change not possible! There is exist relationship with users table');
                    }
                }
                $this->getDesignation()->where('id',$designationID)->update([
                    'company_id' => $company,
                    'title'     =>  $title,
                    'priority'  =>  $priority,
                    'status'    =>  $status,
                    'remarks'   =>  $remarks,
                    'updated_by'=>  $this->user->id,
                    'updated_at'=> date(now())
                ]);
                return back()->with('success','Data update successfully');
            }
            return redirect(route('dashboard'))->with('error','Company not allowed');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function destroy(Request $request)
    {
        try {
            if ($request->isMethod('delete'))
            {
                $request->validate(['id'=>['required','string',Rule::exists('designations','id')]]);
                extract($request->post());
                if (count($this->getDesignation()->where("id",$id)->first()->getUsers))
                {
                    return back()->with('warning','Deletion not possible! A relationship exists.');
                }
                $this->getDesignation()->where("id",$id)->delete();
                return redirect(route('designation.list'))->with('success','Data deleted successfully');
            }
            return back()->with('error','Requested Method Not Allowed');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
