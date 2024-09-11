<?php

namespace App\Http\Controllers\superadmin;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $designations = $this->getDesignation()->orderBY('priority','asc')->get();
            $companies = $this->getCompany()->get();
            return view('back-end.designation.add',compact('designations','companies'));
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
                'priority' =>  ['required','numeric',],
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
}
