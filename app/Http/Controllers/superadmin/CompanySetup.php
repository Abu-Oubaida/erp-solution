<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\company_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanySetup extends Controller
{
    //
    public function index()
    {
        try {
            $companyTypes = company_type::all();
            return view('back-end/programmer/company-setup',compact('companyTypes'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }

    }
    public function companyTypeAdd(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->companyTypeStore($request);
            }
            return $this->index();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function companyTypeStore(Request $request)
    {
        try {
            $request->validate([
                'company_type_title'  => ['required', 'string', 'max:255','unique:'.company_type::class],
                'company_type_status' => ['required', 'numeric'],
                'description' => ['string', 'sometimes','nullable'],
            ]);
            if ($request->isMethod('post'))
            {
                $user = Auth::user();
                extract($request->post());
                company_type::create([
                    'company_type_title'=>  $company_type_title,
                    'status'            =>  ($company_type_status == 1 || $company_type_status == 3)? $company_type_status:0,
                    'remarks'           =>  $description,
                    'created_by'        =>  $user->id,
                    'created_at'        =>  now(),
                ]);
                return back()->with('success','Data insert successfully');
            }
            return back()->withInput();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function companyTypeEdit(Request $request, $companyTypeID)
    {

    }
}
