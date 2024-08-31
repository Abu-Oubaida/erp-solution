<?php

namespace App\Http\Controllers\superadmin;

use App\Exports\EmployeeListPrototypeDataExport;
use App\Exports\UsersSalaryCertificateDataExport;
use App\Exports\UsersSalaryDataExport1;
use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use App\Models\branch;
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
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }else{
                $companies = $this->getCompany()->get();
                $depts = $this->getDepartment()->where('status',1)->get();
                $branches = $this->getBranch()->where('status',1)->get();
                $roles = $this->getRole()->get();
                $designations = $this->getDesignation()->where('status',1)->get();
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
            $request->validate([
                'name'  => ['required', 'string', 'max:255'],
                'phone' => ['required', 'numeric', 'unique:'.User::class],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'company'=> ['required', 'integer', 'exists:company_infos,id'],
                'dept'  => ['required', 'integer', new DepartmentStatusRule],
                'designation'  => ['required', 'integer', new DesignationStatusRule],
                'branch'  => ['required', 'integer', new BranchStatusRule],
                'roll'  => ['required','integer', new RoleStatusRule],
                'joining_date'  => ['required','date'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            if ($request->isMethod('post'))
            {
//                dd($request->post());
                extract($request->post());
                if ($company != $this->user->company_id || !$this->user->isSystemSuperAdmin())
                {
                    return redirect(route('dashboard'))->with('error','Company not allowed');
                }
                $dept = $this->getDepartment()->where('id',$dept)->first();
                $eid = $this->getEid($dept, $joining_date);
                $roles = $this->getRole()->where('id',$roll)->first();
                $user = $this->getUser()->create([
                    'company_id' => $company,
                    'employee_id' => $eid[1],
                    'employee_id_hidden'    => $eid[0],
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
                $user->attachRole($roles->name);
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
                '*.6'   =>  ['required','numeric','unique:users,phone',],
                '*.7'   =>  ['sometimes','nullable','email','unique:users,email'],
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
                    $dept = $this->getDepartment()->where('dept_code',$data[2])->first();
                    $designation = $this->getDesignation()->where('title',$data[3])->first();
                    $branch = $this->getBranch()->where('branch_name',$data[4])->first();
                    $blood = $this->getBloodGroup()->where('blood_type',$data[9])->first();
                    ($blood)? $b_id = $blood->id:$b_id = null;
                    ($data[8])?$status = $data[8]:$status = 0;
                    $eid = $this->getEid($dept, $data[5]);
                    $alreadyInDB = $this->getUser()->where('name',$data[0])->where('phone',$data[6])->where('email',$data[7])->first();
                    if (!$alreadyInDB)
                    {
                        $user = $this->getUser()->create([
                            'company_id' => $this->user->company_id,
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
            if ($this->user->isSystemSuperAdmin())
            {
                $users = $this->getUser()->orderBy('dept_id','asc')->get();
            }
            else {
                $users = $this->getUser()->where('users.status','!=',5)->orderBy('dept_id','asc')->get();
            }
            return view('back-end/user/list',compact('users'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function SingleView($id)
    {
        try {
            $userID = Crypt::decryptString($id);
            $dir = config('app.file_manager_url');
            ($dir)?$fileManagers = scandir($dir):$fileManagers = ['Not Found'];
            unset($fileManagers[0]);
            unset($fileManagers[1]);
            $permissionParents = Permission::where('parent_id',null)->orWhere('is_parent',1)->get();
            $userPermissions = PermissionUser::with('permissionParent')->where('user_id',$userID)->orderBy('permission_name','asc')->get();
            $deptLists = department::where('status',1)->get();
            $filPermission = filemanager_permission::where('status',1)->where('user_id',$userID)->get();
            $roles = Role::get();
            $designations = Designation::where('status',1)->get();
            $branches = branch::where('status',1)->get();
            $user = User::with(['getDepartment','getBranch','getDesignation','roles'])->where('users.id',$userID)->first();
            return view('back-end.user.single-view',compact('user','fileManagers','filPermission','roles','deptLists','permissionParents','userPermissions','designations','branches'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }

    }

    public function UserPerSubmit(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($ref);
            if ($data = filemanager_permission::where("user_id",$id)->where('dir_name',$dir)->first())
            {
                filemanager_permission::where("user_id",$id)->where('dir_name',$dir)->update([
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
                    'status'=>1,
                    'user_id'=>$id,
                    'dir_name'=>$dir,
                    'permission_type'=>$per,
                ]);
            }
            $filPermission = filemanager_permission::where('status',1)->where('user_id',$id)->orderBy('id', 'DESC')->get();
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
                $filPermission = filemanager_permission::where('status',1)->where('user_id',$userID->user_id)->orderBy('id', 'DESC')->get();
                return view("back-end.user._file-permission-list",compact('filPermission'))->render();
            }
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
                if (User::where('id',$userId)->first())
                {
                    User::where('id',$userId)->update([
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
                if (User::where('id',$userId)->first())
                {
                    User::where('id',$userId)->update([
                        'status'=>5,//delete
                    ]);
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
                    DB::table('role_user')->create([
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
                if(User::where('id',$userId)->first())
                {
                    User::where('id',$userId)->update([
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
                $oldData = User::where('id',$userId)->first();
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
                    User::where('id',$userId)->update([
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
                $oldData = User::find($userId);
                User::where('id',$userId)->update([
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
                $oldData = User::find($userId);
                User::where('id',$userId)->update([
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

    public function UserEdit(Request $request,$id)
    {
        try {
            if ($request->isMethod('put'))
            {
                $this->UserUpdate($request);
            }
            $userID = Crypt::decryptString($id);
            $user = User::where('users.id',$userID)->first();
            return view('back-end.user.edit',compact('user'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
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
            $user = User::where('id',$UserID)->first();
            User::where('id',$UserID)->update([
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
    public function getEid($dept, string $joining_date): array
    {
        $joining_year = date('y',strtotime($joining_date));
        $joining_month = date('m',strtotime($joining_date));
        $countOfEmployee = User::where('dept_id', $dept->id)->count();
        $nextEmployee = $countOfEmployee + 1;
        $fourDigit = str_pad($nextEmployee, 3, "0", STR_PAD_LEFT);
        $eid =  $dept->dept_code . $fourDigit;
        while (User::where('employee_id_hidden', $eid)->count()) {
            $nextEmployee++;
            $fourDigit = str_pad($nextEmployee, 3, "0", STR_PAD_LEFT);
            $eid =  $dept->dept_code . $fourDigit;
        }
        $fullEID = $joining_year . $joining_month . $eid;
        return [$eid, $fullEID];
    }

}
