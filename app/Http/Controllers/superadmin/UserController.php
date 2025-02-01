<?php

namespace App\Http\Controllers\superadmin;

use App\Exports\EmployeeListPrototypeDataExport;
use App\Exports\UsersSalaryCertificateDataExport;
use App\Exports\UsersSalaryDataExport1;
use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use App\Models\branch;
use App\Models\company_info;
use App\Models\CompanyModulePermission;
use App\Models\department;
use App\Models\Designation;
use App\Models\DesignationChangeHistory;
use App\Models\filemanager_permission;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserBranchChangeHistory;
use App\Rules\BranchStatusRule;
use App\Rules\DepartmentStatusRule;
use App\Rules\DesignationStatusRule;
use App\Rules\RoleStatusRule;
use App\Rules\UserStatusCheck;
use App\Traits\BranchParent;
use App\Traits\ParentTraitCompanyWise;
use http\Exception\BadConversionException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\If_;

class UserController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    //
    public function create(Request $request)
    {
        try {
            $permission = $this->permissions()->add_user;
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }else{
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                $depts = $this->getDepartment($permission)->where('company_id',$this->user->company_id)->where('status',1)->get();
                $branches = $this->getBranch($permission)->where('company_id',$this->user->company_id)->where('status',1)->get();
                $roles = $this->getRole($permission)->get();
                $designations = $this->getDesignation($permission)->where('company_id',$this->user->company_id)->where('status',1)->get();
                return view('back-end.user.add',compact('depts','branches','roles','designations','companies'))->render();
            }

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    private function store(Request $request)
    {
        try {
            $permission = $this->permissions()->add_user;
            $request->validate([
                'name'  => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric','regex:/^(01[3-9]\d{8})$/', Rule::unique('users','phone')],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users','email')],
//                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users','email')->where(function ($query) use ($request) {
//                    return $query->where('company',$request->post('company'));
//                })],
                'employee_id' => ['required', 'string', 'max:9','min:6',Rule::unique('users','employee_id')->where(function ($query) use ($request) {
                    return $query->where('company',$request->post('company'));
                })],
                'employee_id_hidden' => ['required', 'string', 'max:255',Rule::unique('users','employee_id_hidden')->where(function ($query) use ($request) {
                    return $query->where('company',$request->post('company'));
                })],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'dept'  => ['required', 'integer', new DepartmentStatusRule],
                'designation'  => ['required', 'integer', new DesignationStatusRule],
                'branch'  => ['required', 'integer', new BranchStatusRule],
                'role'  => ['required','integer', new RoleStatusRule],
                'joining_date'  => ['required','date'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
//                if ($company != $this->user->company_id || !$this->user->isSystemSuperAdmin())
//                {
//                    return redirect(route('dashboard'))->with('error','Company not allowed');
//                }
                $dept = $this->getDepartment($permission)->where('id',$dept)->first();
                if (!isset($employee_id) || !isset($employee_id_hidden))
                {
                    $eid = $this->getEid($dept, $joining_date,$company);
                    $employee_id_hidden = $eid[0];
                    $employee_id = $eid[1];
                }
                if ($this->getEid($dept, $joining_date,$company)[1] != $employee_id)
                {
                    $employee_id_hidden = substr($employee_id,4);
                    if (User::where('company',$company)->where('employee_id',$employee_id)->first())
                    {
                        return back()->with('error','Employee ID already exist');
                    }
                }
                $roles = $this->getRole($permission)->where('id',$role)->first();
                $user = $this->getUser($permission)->create([
                    'company' => $company,
                    'employee_id' => $employee_id,
                    'employee_id_hidden'    => $employee_id_hidden,
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'dept_id' => $dept->id,
                    'status' => 1,
                    'designation_id' => $designation,
                    'branch_id' => $branch,
                    'joining_date' => date('y-m-d',strtotime($joining_date)),
                    'password' => Hash::make($request->password),
                ]);
                $user->attachRole($roles->name,'App\Models\User');
                event(new Registered($user));
                return back()->with('success','Account create successfully');
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function excelStore(Request $request)
    {
        try {
            $permission = $this->permissions()->add_user;
            $input = $request->post()['input'];
            unset($input[0]);
            $rules = [
                '*.0'   =>  ['required','string'],
//                '*.1'   =>  ['required','exists:departments,dept_name'],
                '*.1'   =>  ['sometimes','nullable','string'],
                '*.2'   =>  ['required','exists:departments,dept_code'],
                '*.3'   =>  ['required','exists:designations,title'],
                '*.4'   =>  ['required','exists:branches,branch_name'],
                '*.5'   =>  ['required','date'],
                '*.6'   =>  ['required','numeric','regex:/^(01[3-9]\d{8})$/', Rule::unique('users','phone')->where(function ($query) {
                    return $query->where('company',$this->user->company);
                }),],
                '*.7'   =>  ['sometimes','nullable', 'email', 'max:255', Rule::unique('users','email')],
                '*.8'   =>  ['sometimes','nullable','numeric'],
                '*.9'   =>  ['sometimes','nullable','exists:blood_groups,blood_type'],
            ];
            $customMessages = [
                '*.1.exists' => 'The department name does not exist in the Database.',
                '*.2.exists' => 'The department code does not exist in the Database.',
                '*.3.exists' => 'The designations title does not exist in the Database.',
                '*.4.exists' => 'The branches name does not exist in the Database.',
                '*.6.unique' => 'The phone number already exist in the Database.',
                '*.7.unique' => 'The email address already exist in the Database.',
                '*.9.exists' => 'The blood group does not exist in the Database.',
            ];
            $validator = Validator::make($input,$rules,$customMessages);
            // Check if validation fails
            if ($validator->fails()) {
                // Return an error response in JSON format
                $errors = $validator->errors();
                $response = [
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ];
            }else{
                // Return a success response in JSON format
                $unStored=[];
                $alreadyHave=[];
                $stored=[];
                foreach ($input as $key=>$data)
                {
//                    $dept = department::where('dept_name',$data[1])->where('dept_code',$data[2])->first();
                    $dept = $this->getDepartment($permission)->where('dept_code',$data[2])->first();
                    $designation = $this->getDesignation($permission)->where('title',$data[3])->first();
                    $branch = $this->getBranch($permission)->where('branch_name',$data[4])->first();
                    $blood = $this->getBloodGroup()->where('blood_type',$data[9])->first();
                    ($blood)? $b_id = $blood->id:$b_id = null;
                    ($data[8])?$status = $data[8]:$status = 0;
                    $eid = $this->getEid($dept, $data[5],$this->user->company_id);
                    $alreadyInDB = $this->getUser($permission)->where('company',$this->user->company_id)->where('name',$data[0])->where('phone',$data[6])->where('email',$data[7])->first();
                    if (!$alreadyInDB)
                    {
                        $user = $this->getUser($permission)->create([
                            'company' => $this->user->company,
                            'employee_id' => $eid[1],
                            'employee_id_hidden'    => $eid[0],
                            'name' => $data[0],
                            'phone' => $data[6],
                            'email' => $data[7],
                            'dept_id' => $dept->id,
                            'status' => $status,
                            'designation_id' => $designation->id,
                            'branch_id' => $branch->id,
                            'joining_date' => $data[5],
                            'password' => Hash::make('12345'),
                            'blood_id' => $b_id,
                        ]);
                        if ($user)
                        {
                            $user->attachRole('user');
                            $stored[$key] = [
                                'Employee Name'  =>  $data[0],
                                'phone'  =>  $data[6],
                                'email'  =>  $data[7],
                            ];
                        }
                        else{
                            $unStored[$key] = [
                                'Employee Name'  =>  $data[0],
                                'phone'  =>  $data[6],
                                'email'  =>  $data[7],
                            ];
                        }
                    }else{
                        $alreadyHave[$key] = [
                            'Employee Name'  =>  $data[0],
                            'phone'  =>  $data[6],
                            'email'  =>  $data[7],
                        ];
                    }
                }
                $response = [
                    'error' => false,
                    'errorMessage' => $unStored? $unStored:null,
                    'successMessage' => $stored? $stored:null,
                    'alreadyHasMessage' => $alreadyHave? $alreadyHave:null,
                ];
            }
            return response()->json($response, 200);
        }catch (\Throwable $exception)
        {
            $response = [
                'error' => true,
                'code' => $exception->getCode(), // You can use any appropriate error code
                'message' => $exception->getMessage(),
            ];
            return response()->json($response, 200);
        }
    }

    public function show()
    {
        try {
            $permission = $this->permissions()->list_user;
            if ($this->user->isSystemSuperAdmin())
            {
                $users = $this->getUser($permission)->orderBy('dept_id','asc')->get();
            }
            else {
                $users = $this->getUser($permission)->where('users.status','!=',5)->orderBy('dept_id','asc')->get();
            }
//            dd($users->first()->getCompany);
            return view('back-end.user.list',compact('users'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function SingleView($id)
    {
        try {
            $permission = $this->permissions()->view_user;
            $userID = Crypt::decryptString($id);
//            dd($this->list_user);
            $user = $this->getUser($permission)->where('id',$userID)->first();
//            $companyWiseParentPermission = CompanyModulePermission::select('module_parent_id')->where('company_id',$user->company)->distinct()->get();
//            $permissionParents = Permission::whereIn('id',$companyWiseParentPermission)->where('parent_id',null)->get();
            $userPermissions = PermissionUser::with(['permissionParent','company'])->where('user_id',$userID)->orderBy('permission_name','asc')->get();
//            $deptLists = department::whereIn('company_id',$this->getUserCompanyPermissionArray($userID))->where('status',1)->get();
            $deptLists = $this->getDepartment($permission)->where('company_id',$user->company)->where('status',1)->get();
            $filPermission = filemanager_permission::with(['company'])->where('status',1)->where('user_id',$userID)->get();
            $roles = Role::where('company_id',$user->company)->get();
            $designations = $this->getDesignation($permission)->where('company_id',$user->company)->where('status',1)->get();
            $userCompanies = company_info::whereIn('id',$this->getUserCompanyPermissionArray($userID))->get();
            $branches = $this->getBranch($permission)->where('company_id',$user->company)->where('status',1)->get();
            return view('back-end.user.single-view',compact('user','filPermission','roles','deptLists','userPermissions','designations','branches','userCompanies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }

    }

    public function UserEdit(Request $request,$id)
    {
//        try {
            $permission = $this->permissions()->edit_user;
            if ($request->isMethod('put'))
            {
                $this->UserUpdate($request);
            }
            $userID = Crypt::decryptString($id);
            $user = $this->getUser($this->permissions()->edit_user)->where('users.id',$userID)->first();
            $userPermissions = PermissionUser::with(['permissionParent','company'])->where('user_id',$userID)->orderBy('permission_name','asc')->get();
//            $deptLists = department::whereIn('company_id',$this->getUserCompanyPermissionArray($userID))->where('status',1)->get();
            $deptLists = $this->getDepartment($permission)->where('company_id',$user->company)->where('status',1)->get();
            $filPermission = filemanager_permission::with(['company'])->where('status',1)->where('user_id',$userID)->get();
            $roles = Role::where('company_id',$user->company)->get();
            $designations = $this->getDesignation($permission)->where('company_id',$user->company)->where('status',1)->get();
            $userCompanies = company_info::whereIn('id',$this->getUserCompanyPermissionArray($userID))->get();
            $branches = $this->getBranch($permission)->where('company_id',$user->company)->where('status',1)->get();
            $roleNew = $this->user->roles->first();
            return view('back-end.user.edit',compact('user','filPermission','roles','deptLists','userPermissions','designations','branches','userCompanies','roleNew'))->render();
//        }catch (\Throwable $exception)
//        {
//            return back()->with('error',$exception->getMessage());
//        }
    }

    public function UserUpdate(Request $request)
    {
        try {
            $request->validate([
                'name'  => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', Rule::unique('users')->ignore(Crypt::decryptString($request->id))],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Crypt::decryptString($request->id))],
            ]);
            extract($request->post());
            $UserID = Crypt::decryptString($id);
            $user = $this->getUser($this->permissions()->edit_user)->where('id',$UserID)->first();
            $this->getUser($this->permissions()->delete_user)->where('id',$UserID)->update([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,

            ]);
            return back()->with('success','Data update successfully!');

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function UserPerSubmit(Request $request)
    {
        try {
            $request->validate([
                'company' => ['required','numeric','exists:company_infos,id'],
                'per' => ['required','string',],
                'dir' => ['required','string',],
                'ref' => ['required','string',],
            ]);
            extract($request->post());
            $id = Crypt::decryptString($ref);
            $user = User::where('id',$id)->first();
            if ($data = filemanager_permission::where("user_id",$id)->where('dir_name',$dir)->first())
            {
                filemanager_permission::where("user_id",$id)->where('dir_name',$dir)->update([
                    'company_id' => $company,
                    'status'=>1,
                    'permission_type'=>$per,
                ]);
//                echo json_encode(array(
//                    'error' => array(
//                        'msg' => "Data already exist",
//                        'code' => 403,
//                    )
//                ));
            }
            else{
                filemanager_permission::create([
                    'company_id' => $company,
                    'status'=>1,
                    'user_id'=>$user->id,
                    'dir_name'=>$dir,
                    'permission_type'=>$per,
                ]);
            }
            $filPermission = filemanager_permission::with(['company'])->where('status',1)->where('user_id',$id)->orderBy('id', 'DESC')->get();
            return view("back-end.user._file-permission-list",compact('filPermission'))->render();
        }catch (\Throwable $exception)
        {
            echo json_encode(array(
                'error' => array(
                    'msg' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                )
            ));
        }
    }

    public function UserPerDelete(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($ref);
            if ($id)
            {
                $userID = filemanager_permission::where('id',$id)->select('user_id')->first();
                filemanager_permission::where('id',$id)->update(['status'=>0]);
                $filPermission = filemanager_permission::with(['company'])->where('status',1)->where('user_id',$userID->user_id)->orderBy('id', 'DESC')->get();
                $view = view("back-end.user._file-permission-list",compact('filPermission'))->render();
                return response()->json([
                   'status' => 'success',
                   'data' => $view,
                   'message' => 'Record deleted successfully.',
                ]);

            }
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);

        }
    }

    public function userStatusChange(Request $request)
    {
        if ($request->isMethod('post'))
        {
            try {
                extract($request->post());
                $userId = Crypt::decryptString($id);
                if ($user_status == 1)
                {
                    $status = 1;
                }else {
                    $status = 0;
                }
                if ($this->getUser($this->permissions()->edit_user)->where('id',$userId)->first())
                {
                    $this->getUser($this->permissions()->edit_user)->where('id',$userId)->update([
                        'status'=>$status,
                    ]);
                    return back()->with('success','Update successfully!');
                }
            }catch (\Throwable $exception)
            {
                return back()->with('error',$exception->getMessage());
            }
        }
        return back()->with('error','Access Denied!');
    }
    public function UserDelete(Request $request)
    {
        if ($request->isMethod('delete'))
        {
            try {
                extract($request->post());
                $userId = Crypt::decryptString($id);
                if ($this->getUser($this->permissions()->delete_user)->where('id',$userId)->first())
                {
                    $this->getUser($this->permissions()->delete_user)->where('id',$userId)->update([
                        'status'=>5,//delete
                    ]);
//                    if (Auth::user()->roles->first()->name == 'systemsuperadmin')
//                    {
//
//                    }
                    return back()->with('warning','User Deleted!');
                }
            }catch (\Throwable $exception)
            {
                return back()->with('error',$exception->getMessage());
            }
        }
        return back()->with('error','Access Denied!');
    }
    public function userRoleChange(Request $request)
    {
        if ($request->isMethod('post'))
        {
            try {
                extract($request->post());
                $userId = Crypt::decryptString($id);
                if(DB::table('role_user')->where('user_id',$userId)->first())
                {
                    DB::table('role_user')->where('user_id',$userId)->update(['role_id'=>$user_role]);
                }
                else{
                    DB::table('role_user')->insert([
                        'role_id'=>$user_role,
                        'user_id'=>$userId,
                        'user_type'=>'App\Models\User'
                    ]);
                }
                return back()->with('success','Data update successfully');
            }catch (\Throwable $exception)
            {
                return back()->with('error',$exception->getMessage());
            }
        }
        return back()->with('error','Access Denied!');
    }
    public function userPasswordChange(Request $request)
    {
        if ($request->isMethod('post'))
        {
            try {
                extract($request->post());
                $userId = Crypt::decryptString($id);
                if($this->getUser($this->permissions()->edit_user)->where('id',$userId)->first())
                {
                    $this->getUser($this->permissions()->edit_user)->where('id',$userId)->update([
                        "password" => Hash::make($password)
                    ]);
                }
                return back()->with('success','Data update successfully');

            }catch (\Throwable $exception)
            {
                return back()->with('error',$exception->getMessage());
            }
        }
        return back()->with('error','Access Denied!');
    }
    public function userDepartmentChange(Request $request)
    {
        if ($request->isMethod('put'))
        {
            try {
                extract($request->post());
                $userId = Crypt::decryptString($id);
                $oldData = $this->getUser($this->permissions()->edit_user)->where('id',$userId)->first();
                if (!($oldData->joining_date))
                {
                    return back()->with('error','Empty employee joining date');
                }
                if ($oldData->dept_id == $dept_id)
                {
                    return back()->with('warning','Old and New department value are same!');
                }
                if($dept = department::where('id',$dept_id)->first())
                {
                    $eid = $this->getEid($dept,$oldData->joining_date);
                    $this->getUser($this->permissions()->edit_user)->where('id',$userId)->update([
                        "dept_id" => $dept_id,
                        'employee_id' => $eid[1],
                        'employee_id_hidden'    => $eid[0],
                    ]);
                    DB::table('department_transfer_histories')->insert([
                        'new_employee_id'=>$eid[1],
                        'old_employee_id'=>$oldData->employee_id,
                        'transfer_user_id'=>$userId,
                        'new_dept_id'=>$dept_id,
                        'from_dept_id'=>$oldData->dept_id,
                        'transfer_by'=>Auth::user()->id,
                        'created_at'=>now(),
                    ]);
                }
                return back()->with('success','Data update successfully');

            }catch (\Throwable $exception)
            {
                return back()->with('error',$exception->getMessage());
            }


        }
        return back()->with('error','Access Denied!');
    }

    public function userDesignationChange(Request $request)
    {
        try {
            if ($request->isMethod('put'))
            {
                $request->validate([
                    'id'  =>  ['required','string',new UserStatusCheck],
                    'designation_id'  =>  ['required','integer', new DesignationStatusRule]
                ]);
                extract($request->post());
                $userId = Crypt::decryptString($id);
                $oldData = $this->getUser($this->permissions()->edit_user)->find($userId);
                $this->getUser($this->permissions()->edit_user)->where('id',$userId)->update([
                    'designation_id'   =>  $designation_id,
                ]);
                DesignationChangeHistory::create([
                    'transfer_user_id'=>$userId,
                    'new_designation_id'=>$designation_id,
                    'old_designation_id'=>$oldData->designation_id,
                    'transfer_by'=>Auth::user()->id,
                ]);
                return back()->with('success','Data updated successfully!');
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function userBranchChange(Request $request)
    {
        try {
            if ($request->isMethod('put'))
            {
                $request->validate([
                    'id'  =>  ['required','string',new UserStatusCheck],
                    'branch_id'  =>  ['required','integer', new BranchStatusRule]
                ]);
                extract($request->post());
                $userId = Crypt::decryptString($id);
                $oldData = $this->getUser($this->permissions()->edit_user)->find($userId);
                $this->getUser($this->permissions()->edit_user)->where('id',$userId)->update([
                    'branch_id'   =>  $branch_id,
                ]);
                UserBranchChangeHistory::create([
                    'transfer_user_id'=>$userId,
                    'new_branch_id'=>$branch_id,
                    'old_designation_id'=>$oldData->branch_id,
                    'transfer_by'=>Auth::user()->id,
                ]);
                return back()->with('success','Data updated successfully!');
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function exportEmployeeDataPrototype()
    {
        return Excel::download(new EmployeeListPrototypeDataExport,'employee.xlsx');
    }

    /**
     * @param $dept
     * @param string $joining_year
     * @param string $joining_month
     * @return string[]
     */
    public function getEid($dept, string $joining_date, $company_id): array
    {
        $joining_year = date('y',strtotime($joining_date));
        $joining_month = date('m',strtotime($joining_date));
        $countOfEmployee = User::where('company',$company_id)->where('dept_id', $dept->id)->count();
        $nextEmployee = $countOfEmployee + 1;
        $fourDigit = str_pad($nextEmployee, 3, "0", STR_PAD_LEFT);
        $eid =  $dept->dept_code . $fourDigit;
        while (User::where('company',$company_id)->where('employee_id_hidden', $eid)->count()) {
            $nextEmployee++;
            $fourDigit = str_pad($nextEmployee, 3, "0", STR_PAD_LEFT);
            $eid =  $dept->dept_code . $fourDigit;
        }
        $fullEID = $joining_year . $joining_month . $eid;
        return [$eid, $fullEID];
    }

    public function getEmployeeId(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'department_id' => ['required','integer', new DepartmentStatusRule],
                    'company_id' => ['required', 'integer', 'exists:company_infos,id'],
                    'joining_date' => ['required', 'date_format:Y-m-d','date', 'before_or_equal:today'],
                ]);
                extract($request->post());
                $dept = department::where('company_id',$company_id)->where('id',$department_id)->first();
                if ($dept)
                {
                    $employee_id = $this->getEid($dept, $joining_date, $company_id);
                    return response()->json([
                        'status' => 'success',
                        'data' => $employee_id,
                        'message' => 'Request processed successfully!'
                    ]);
                }
                return response()->json([
                    'status'    =>  'error',
                    'message'   =>  'Department not found for selected company!'
                ],401);
            }
            return response()->json([
                'status'    =>  'error',
                'message'   =>  'Request method not allowed!'
            ],401);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ],401);
        }
    }

    public function changeUserCompany(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','integer', 'exists:company_infos,id'],
                ]);
                extract($request->post());
                $branches = branch::where('company_id',$company_id)->where('status',1)->get();
                $departments = department::where('company_id',$company_id)->where('status',1)->get();
                $designations = Designation::where('company_id',$company_id)->where('status',1)->get();
                if (Auth::user()->roles()->first()->name == 'systemsuperadmin')
                {
                    $roles = Role::where('company_id', $company_id)
                        ->orWhere(function ($query) use ($company_id) {
                            $query->whereNull('company_id') // For system-wide roles
                            ->whereIn('name', ['systemsuperadmin', 'systemadmin', 'superadmin','admin','user']);
                        })->get();
                }else if (Auth::user()->roles()->first()->name == 'superadmin'){
                    $roles = Role::where('company_id', $company_id)
                        ->orWhere(function ($query) use ($company_id) {
                            $query->whereNull('company_id') // For system-wide roles
                            ->whereIn('name', ['superadmin','admin','user']);
                        })->get();
                }else {
                    $roles = Role::where('company_id', $company_id)
                        ->orWhere(function ($query) use ($company_id) {
                            $query->whereNull('company_id') // For system-wide roles
                            ->whereIn('name', ['user']);
                        })->get();
                }
                return response()->json([
                    'status'    =>  'success',
                    'data'      => ['branches'=>$branches,'departments'=>$departments,'designations'=>$designations,'roles'=>$roles],
                    'message'   =>  'Request processed successfully!'
                ]);
            }
            return response()->json([
                'status'    =>  'error',
                'message'   =>  'Request method not allowed!'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
}
