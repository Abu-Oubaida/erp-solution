<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\company_info;
use App\Models\company_type;
use App\Models\User;
use App\Models\UserCompanyPermission;
use App\Traits\DeleteFileTrait;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\File;

class CompanySetup extends Controller
{
    use DeleteFileTrait, ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    private $imagePath = "image/logo/";
    //
    public function index()
    {
        try {

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
                $this->companyTypeStore($request);
            }
            return view('back-end/programmer/company-type-add')->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function companyTypeList()
    {
        try {
            $companyTypes = company_type::with(['createdBY','updatedBY'])->get();
            return view('back-end/programmer/company-type-list',compact('companyTypes'))->render();
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
        try {
            $id = Crypt::decryptString($companyTypeID);
            if ($request->isMethod('put'))
            {
                $this->companyTypeUpdate($request, $id);
            }
            $editID = company_type::where('id',$id)->first();
            if (!$editID)
            {
                return back()->with('error','Data Not Found!');
            }
            $companyTypes = company_type::with(['createdBY','updatedBY'])->get();
            return view('back-end/programmer/company-type-edit',compact('editID','companyTypes'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function companyTypeUpdate(Request $request, $companyTypeID)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'company_type_title'  => ['required', 'string', 'max:255',Rule::unique('company_types')->ignore($companyTypeID)],
                'company_type_status' => ['required', 'numeric'],
                'description' => ['string', 'sometimes','nullable'],
            ]);
            extract($request->post());
            company_type::where('id',$companyTypeID)->update([
                'company_type_title'=>  $company_type_title,
                'status'            =>  ($company_type_status == 1 || $company_type_status == 3)? $company_type_status:0,
                'remarks'           =>  $description,
                'updated_by'        =>  $user->id,
                'updated_at'        =>  now(),
            ]);
            return back()->with('success','Data updated successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function companyTypeDelete(Request $request)
    {
        try {
            $request->validate([
                'id'    =>  ['string','required'],
            ]);
            extract($request->post());
            $deleteID = Crypt::decryptString($id);
            $company = company_type::with(['companies'])->find($deleteID);
            if(count($company->companies))
            {
                return back()->with('error','A relationship exists between other tables. Data delete not possible');
            }
            company_type::where('id',$deleteID)->delete();
            return back()->with('success','Data deleted successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function companyAdd(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $this->companyStore($request);
            }
            $companyTypes = company_type::with(['createdBY','updatedBY'])->get();
            $companies = company_info::with(['createdBY','updatedBY','companyType'])->get();
            return view('back-end/programmer/company-setup',compact('companyTypes','companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    private function companyStore(Request $request)
    {
        $request->validate([
            'company_name'  => ['required', 'string', 'max:255','unique:company_infos,company_name'],
            'company_short_name' => ['required', 'string','regex:/(^[A-Za-z0-9 ]+$)+/','unique:company_infos,company_code'],
            'company_type_id'   =>  ['required', 'numeric','exists:company_types,id'],
            'contract_person' => ['string', 'required','max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:company_infos,email'],
            'contract_person_phone' => ['required', 'numeric'],
            'company_phone' => ['sometimes','nullable', 'numeric', 'unique:company_infos,phone'],
            'logo'    =>  ['sometimes','nullable','max:2048'],
            'logo_sm'    =>  ['sometimes','nullable','max:1024'],
            'logo_icon'    =>  ['sometimes','nullable','max:1024'],
            'cover'    =>  ['sometimes','nullable','max:2048'],
            'location'    =>  ['sometimes','nullable','string'],
            'remarks'    =>  ['sometimes','nullable','string'],
        ]);
        try {
            extract($request->post());
            $user = Auth::user();
            if ($request->hasFile('logo'))
            {
                $file = $request->file('logo');
                $logo_name = $company_short_name."_logo_".$file->getClientOriginalName();
                $logo_location = $file->move($this->imagePath,$logo_name);

            }
            if ($request->hasFile('logo_sm'))
            {
                $file = $request->file('logo_sm');
                $logo_sm_name = $company_short_name."_logo_sm_".$file->getClientOriginalName();
                $logo_sm_location = $file->move($this->imagePath,$logo_sm_name);

            }
            if ($request->hasFile('logo_icon'))
            {
                $file = $request->file('logo_icon');
                $logo_icon_name = $company_short_name."_logo_icon_".$file->getClientOriginalName();
                $logo_icon_location = $file->move($this->imagePath,$logo_icon_name);
            }
            if ($request->hasFile('cover'))
            {
                $file = $request->file('cover');
                $cover_name = $company_short_name."_cover_".$file->getClientOriginalName();
                $cover_location = $file->move($this->imagePath,$cover_name); // Adjust the

            }
            company_info::create([
                'status'=>1,
                'company_name'=>$company_name,
                'company_type_id'=>$company_type_id,
                'contract_person_name'=>$contract_person,
                'company_code'=>$company_short_name,
                'phone'=>$company_phone,
                'contract_person_phone'=>$contract_person_phone,
                'email'=>$email,
                'location'=>$location,
                'remarks'=>$remarks,
                'logo'=>isset($logo_name)?$this->imagePath.$logo_name:'',
                'logo_sm'=>isset($logo_sm_name)?$this->imagePath.$logo_sm_name:'',
                'logo_icon'=>isset($logo_icon_name)?$this->imagePath.$logo_icon_name:'',
                'cover'=>isset($cover_name)?$this->imagePath.$cover_name:'',
                'created_by'=>$user->id,
                'created_at'=>now(),
            ]);
            return back()->with('success','Data add successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function companyList()
    {
        try {
            $companies = company_info::with(['createdBY','updatedBY','companyType'])->get();
            return view('back-end.programmer.company-list',compact('companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }

    }
    public function companyEdit(Request $request, $companyID)
    {
        try {
            if ($request->isMethod('put'))
            {
                $this->companyUpdate($request, $companyID);
            }
            $companyTypes = company_type::where('status',1)->get();
            $companies = company_info::with(['createdBY','updatedBY','companyType'])->get();
            $c_id = Crypt::decryptString($companyID);
            $edit_company = company_info::where('id',$c_id)->first();
            if (!$edit_company)
            {
                return back()->with('error','Company Not Found!');
            }
            return view('back-end/programmer/company-edit',compact('edit_company','companies','companyTypes'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function companyUpdate(Request $request, $companyID)
    {
        $id = Crypt::decryptString($companyID);
        $request->validate([
            'company_name'  => ['required', 'string', 'max:255',Rule::unique('company_infos','company_name')->ignore($id)],
            'company_short_name' => ['required', 'string','regex:/(^[A-Za-z0-9 ]+$)+/',Rule::unique('company_infos','company_code')->ignore($id)],
            'company_type_id'   =>  ['required', 'numeric','exists:company_types,id'],
            'contract_person' => ['string', 'required','max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('company_infos','email')->ignore($id)],
            'contract_person_phone' => ['required', 'numeric'],
            'company_phone' => ['sometimes','nullable', 'numeric', Rule::unique('company_infos','phone')->ignore($id)],
            'logo'    =>  ['sometimes','nullable','max:2048'],
            'logo_sm'    =>  ['sometimes','nullable','max:1024'],
            'logo_icon'    =>  ['sometimes','nullable','max:1024'],
            'cover'    =>  ['sometimes','nullable','max:2048'],
            'location'    =>  ['sometimes','nullable','string'],
            'remarks'    =>  ['sometimes','nullable','string'],
        ]);
        try {
            extract($request->post());
            $user = Auth::user();
            $company = company_info::where('id',$id)->first();
            if ($request->hasFile('logo'))
            {
                $file = $request->file('logo');
                $logo_name = $company_short_name."_logo_".$file->getClientOriginalName();
                $this->deleteFile(public_path($this->imagePath.$logo_name));
                $logo_location = $file->move($this->imagePath,$logo_name);

            }
            if ($request->hasFile('logo_sm'))
            {
                $file = $request->file('logo_sm');
                $logo_sm_name = $company_short_name."_logo_sm_".$file->getClientOriginalName();
                $this->deleteFile(public_path($this->imagePath.$logo_sm_name));
                $logo_sm_location = $file->move($this->imagePath,$logo_sm_name);

            }
            if ($request->hasFile('logo_icon'))
            {
                $file = $request->file('logo_icon');
                $logo_icon_name = $company_short_name."_logo_icon_".$file->getClientOriginalName();
                $this->deleteFile(public_path($this->imagePath.$logo_icon_name));
                $logo_icon_location = $file->move($this->imagePath,$logo_icon_name);
            }
            if ($request->hasFile('cover'))
            {
                $file = $request->file('cover');
                $cover_name = $company_short_name."_cover_".$file->getClientOriginalName();
                $this->deleteFile(public_path($this->imagePath.$cover_name));
                $cover_location = $file->move($this->imagePath,$cover_name); // Adjust the

            }
            company_info::where('id',$id)->update([
                'status'=>1,
                'company_name'=>$company_name,
                'company_type_id'=>$company_type_id,
                'contract_person_name'=>$contract_person,
                'company_code'=>$company_short_name,
                'phone'=>$company_phone,
                'contract_person_phone'=>$contract_person_phone,
                'email'=>$email,
                'location'=>$location,
                'remarks'=>$remarks,
                'logo'=>isset($logo_name)?$this->imagePath.$logo_name:'',
                'logo_sm'=>isset($logo_sm_name)?$this->imagePath.$logo_sm_name:'',
                'logo_icon'=>isset($logo_icon_name)?$this->imagePath.$logo_icon_name:'',
                'cover'=>isset($cover_name)?$this->imagePath.$cover_name:'',
                'created_by'=>$user->id,
                'created_at'=>now(),
            ]);
            return back()->with('success','Data add successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function companyDelete(Request $request)
    {
        try {
            if ($request->isMethod('delete'))
            {
                $id = Crypt::decryptString($request->post('id'));
                $company = company_info::with(['users'])->where('id',$id)->first();
                if (count($company->users)>0)
                {
                    return back()->with('warning','A relationship exists between other tables. Data delete not possible');
                }
                $this->deleteFile(public_path($this->imagePath. $company->logo));
                $this->deleteFile(public_path($this->imagePath . $company->logo_sm));
                $this->deleteFile(public_path($this->imagePath . $company->logo_icon));
                $this->deleteFile(public_path($this->imagePath . $company->cover));
                $company->delete();
                return back()->with('success','Data deleted successfully.');
            }
            return back()->with('error','Requested method not allowed.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function userCompanyPermission(Request $request,$companyID)
    {
        try {
            $cID = Crypt::decryptString($companyID);
            if ($request->isMethod('post'))
            {
                return $this->userCompanyPermissionStore($request, $cID);
            }
            $company = $this->getCompany()->where('id',$cID)->first();
            $selfUsersID = $company->users->pluck('id')->unique()->toArray();
            $users = $this->getUser()->where('status',1)->whereNot('id',$this->user->id)->get();
            $roles = $this->getRole()->get();
            return view('back-end.control-panel.company.user-permission.add-permission',compact('users','company','roles'))->render();
        } catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function userCompanyPermissionStore(Request $request,$companyID)
    {
        try {
            $request->validate([
                'role' => ['required','numeric','exists:roles,id'],
                'users' => ['required','array'],
                'users.*' => ['nullable','string','exists:users,id'],
            ]);
            extract($request->post());
            foreach ($users as $user)
            {
                if (!(UserCompanyPermission::where('user_id',$user)->where('company_id',$companyID)->exists()))
                {
                    UserCompanyPermission::create([
                        'role_id'=>$role,
                        'user_id'=>$user,
                        'company_id'=>$companyID,
                        'created_at'=>now(),
                        'created_by'=>$this->user->id,
                    ]);
                }
            }
            return back()->with('success','Data added successfully.');
        } catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function userCompanyPermissionDelete(Request $request)
    {
        try {
            if ($request->isMethod('delete'))
            {
                $id = Crypt::decryptString($request->post('id'));
                UserCompanyPermission::where('id',$id)->delete();
                return back()->with('success','Data deleted successfully.');
            }
            return back()->with('success','Requested method not allowed.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
