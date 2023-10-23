<?php

namespace App\Http\Controllers\superadmin;

use App\Exports\UsersSalaryCertificateDataExport;
use App\Exports\UsersSalaryDataExport1;
use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\department;
use App\Models\filemanager_permission;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Role;
use App\Models\User;
use http\Exception\BadConversionException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Stmt\If_;

class UserController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }else{
                $depts = department::where('status',1)->get();
                $branches = branch::where('status',1)->get();
                $roles = Role::get();
                return view('back-end.user.add',compact('depts','branches','roles'));
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
                'dept'  => ['required', 'exists:departments,id'],
                'branch'  => ['required', 'exists:branches,id'],
                'roll'  => ['required','numeric', 'exists:roles,id'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                if ($user = User::where('phone',$phone)->orWhere('email',$email)->first())
                {
                    return back()->with('warning','Duplicate email/phone data found!')->withInput();
                }
                if (!($branches = branch::where('id',$branch)->where('status',1)->first()))
                {
                    return back()->with('error','Branch not found!')->withInput();
                }
                if (!($depts = department::where('status',1)->where('id',$dept)->first()))
                {
                    return back()->with('error','Department not found!')->withInput();
                }
                if (!($roles = Role::where('id',$roll)->first()))
                {
                    return back()->with('error','User roll not found!')->withInput();
                }
                if ($branches->branch_type == 'head office') $header = 'H'; else $header = "P";
                $priviusUsers = User::where('status',1)->get();
                $priviusUserCount = count($priviusUsers);
                $threeDigitId = str_pad($priviusUserCount, 3, '0', STR_PAD_LEFT);
                $nid = $header.$depts->dept_code.$threeDigitId;
                while (User::where('employee_id',$nid)->first())
                {
                    $threeDigitId++;
                    $nid = $header.$depts->dept_code.(str_pad($threeDigitId, 3, '0', STR_PAD_LEFT));
                }

//            dd($priviusUserCount >= 10 && $priviusUserCount < 100);
//                if ($priviusUserCount < 10)
//                {
//                    $priviusUserCount++;
//                    $empID = ($depts->dept_code."00");
//                }
//                elseif ($priviusUserCount >= 10 && $priviusUserCount < 100)
//                {
//                    $priviusUserCount++;
//                    $empID = ($depts->dept_code."0");
//                }
//                else {
//                    $priviusUserCount++;
//                    $empID = $depts->dept_code;
//                }

                $user = User::create([
                    'employee_id' => $nid,
                    'name' => $name,
                    'phone' => $phone,
                    'email' => $email,
                    'dept_id' => $depts->id,
                    'status' => 1,
                    'branch_id' => $branches->id,
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

    public function show()
    {
        try {
            $users = User::leftJoin('departments as dept','dept.id','users.dept_id')->leftJoin('role_user as ur','ur.user_id','users.id')->leftJoin('roles as r','r.id','ur.role_id')->where('users.status','!=',5)->select('dept.dept_name','r.display_name','users.*')->get();
            return view('back-end/user/list',compact('users'));
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

            $fileManagers = scandir($dir);
            unset($fileManagers[0]);
            unset($fileManagers[1]);
            $permissionParents = Permission::where('parent_id',null)->get();
            $userPermissions = PermissionUser::with('permissionParent')->where('user_id',$userID)->orderBy('permission_name','asc')->get();
            $deptlist = department::where('status',1)->get();
            $filPermission = filemanager_permission::where('status',1)->where('user_id',$userID)->get();
            $roles = Role::get();
            $user = User::leftJoin('departments as dept','dept.id','users.dept_id')->leftJoin('role_user as ur','ur.user_id','users.id')->leftJoin('roles as r','r.id','ur.role_id')->where('users.id',$userID)->select('dept.dept_name','r.display_name','r.id as role_id','users.*')->first();
//        dd($user);
            return view('back-end.user.single-view',compact('user','fileManagers','filPermission','roles','deptlist','permissionParents','userPermissions'));
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
            return view("back-end.user._file-permission-list",compact('filPermission'));
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
                return view("back-end.user._file-permission-list",compact('filPermission'));
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
                if($role_users = DB::table('role_user')->where('role_id',$user_role)->first())
                {
                    DB::table('role_user')->where('user_id',$userId)->update(['role_id'=>$user_role]);
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
        if ($request->isMethod('post'))
        {
            try {
                extract($request->post());
                $userId = Crypt::decryptString($id);
                $oldData = User::where('id',$userId)->first();
                if(department::where('id',$dept_id)->first())
                {
                    User::where('id',$userId)->update([
                        "dept_id" => $dept_id
                    ]);
                    DB::table('department_transfer_histories')->insert([
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

    public function UserEdit(Request $request,$id)
    {
        try {
            if ($request->isMethod('put'))
            {
                $this->UserUpdate($request);
            }
            $userID = Crypt::decryptString($id);
            $depts = department::where('status',1)->get();
            $roles = Role::get();
            $branches = branch::where('status',1)->get();
            $user = User::leftJoin('departments as dept','dept.id','users.dept_id')->leftJoin('role_user as ur','ur.user_id','users.id')->leftJoin('roles as r','r.id','ur.role_id')->where('users.id',$userID)->select('dept.dept_name','r.display_name','r.id as role_id','users.*')->first();
//            dd($user->phone);
            return view('back-end.user.edit',compact('user','roles','depts','branches'));
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
                'dept'  => ['required', 'exists:departments,id'],
                'branch'  => ['required', 'exists:branches,id'],
                'roll'  => ['required','numeric', 'exists:roles,id'],
            ]);
            extract($request->post());
            $UserID = Crypt::decryptString($id);
            $user = User::where('id',$UserID)->first();
            $depts = department::where('id',$dept)->first();
            $branches = branch::where('id',$branch)->first();
            if (!$depts)
            {
                return back()->with('error','Department not found!')->withInput();
            }
            if (!$branches)
            {
                return back()->with('error','Branches not found!')->withInput();
            }

            if ($user->dept_id != $dept)
            {
                DB::table('department_transfer_histories')->insert([
                    'transfer_user_id'=>$UserID,
                    'new_dept_id'=>$dept,
                    'from_dept_id'=>$user->dept_id,
                    'transfer_by'=>Auth::user()->id,
                    'created_at'=>now(),
                ]);
            }
            if ($user->branch_id != $branch)
            {
                DB::table('branch_transfer_histories')->insert( [
                    'transfer_user_id'=>$UserID,
                    'new_branch_id'=>$branch,
                    'from_branch_id'=>$user->branch_id,
                    'transfer_by'=>Auth::user()->id,
                    'created_at'=>now(),
                ]);
            }
            User::where('id',$UserID)->update([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'dept_id' => $depts->id,
                'branch_id' => $branches->id,

            ]);
            return back()->with('success','Data update successfully!');

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function exportUserSalaryPrototype()
    {
        return Excel::download(new UsersSalaryCertificateDataExport,'salary certificate input data.xlsx');
    }
}
