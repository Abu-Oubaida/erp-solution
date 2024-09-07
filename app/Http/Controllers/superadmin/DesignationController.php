<?php

namespace App\Http\Controllers\superadmin;
use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $designations = Designation::orderBY('priority','asc')->get();
            $companies = $this->getCompany()->get();
            return view('back-end/designation/add',compact('designations','companies'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function store(Request $request)
    {
        try {
            $request->validate([
                'title' =>  ['required','string','unique:designations,title',],
                'priority' =>  ['required','numeric',],
                'status' =>  ['required','numeric',],
                'remarks' =>  ['sometimes','nullable','string',],
            ]);
            extract($request->post());
            $user = Auth::user();
            ($status == 1)?$status_new = 1:$status_new = 0;
            Designation::create([
                'title'     =>  $title,
                'priority'  =>  $priority,
                'status'    =>  $status_new,
                'remarks'   =>  $remarks,
                'created_by'=>  $user->id,
            ]);
            return back()->with('success','Data save successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
