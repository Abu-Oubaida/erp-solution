<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\department;
use App\Models\filemanager_permission;
use App\Models\Role;
use App\Models\User;
use http\Exception\BadConversionException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

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
                $priviusUsers = User::where('status',1)->where('dept_id',$dept)->get();
                $priviusUserCount = count($priviusUsers);
//            dd($priviusUserCount >= 10 && $priviusUserCount < 100);
                if ($priviusUserCount < 10)
                {
                    $priviusUserCount++;
                    $empID = ($header.$depts->dept_code."00").$priviusUserCount;
                }
                elseif ($priviusUserCount >= 10 && $priviusUserCount < 100)
                {
                    $priviusUserCount++;
                    $empID = ($header.$depts->dept_code."0").$priviusUserCount;
                }
                else {
                    $priviusUserCount++;
                    $empID = $header.$depts->dept_code.$priviusUserCount;
                }
                $user = User::create([
                    'employee_id' => $empID,
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
            $users = User::leftJoin('departments as dept','dept.id','users.dept_id')->leftJoin('role_user as ur','ur.user_id','users.id')->leftJoin('roles as r','r.id','ur.role_id')->select('dept.dept_name','r.display_name','users.*')->get();
            return view('back-end/user/list',compact('users'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function SingleView($id)
    {
        $userID = Crypt::decryptString($id);
        $dir = "E:/LocalServer/htdocs/chl/public/file-manager";
        $fileManagers = scandir($dir);
        unset($fileManagers[0]);
        unset($fileManagers[1]);
        $filPermission = filemanager_permission::where('status',1)->where('user_id',$userID)->get();
        $user = User::leftJoin('departments as dept','dept.id','users.dept_id')->leftJoin('role_user as ur','ur.user_id','users.id')->leftJoin('roles as r','r.id','ur.role_id')->where('users.id',$userID)->select('dept.dept_name','r.display_name','users.*')->first();
//        dd($user);
        return view('back-end.user.single-view',compact('user','fileManagers','filPermission'));
    }

    public function UserPerSubmit(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($ref);
            if ($data = filemanager_permission::where("user_id",$id)->where('dir_name',$dir)->first())
            {
                echo json_encode(array(
                    'error' => array(
                        'msg' => "Data already exist",
                        'code' => 403,
                    )
                ));
            }
            else{
                filemanager_permission::create([
                    'status'=>1,
                    'user_id'=>$id,
                    'dir_name'=>$dir,
                    'permission_type'=>$per,
                ]);
                $filPermission = filemanager_permission::where('status',1)->where('user_id',$id)->orderBy('id', 'DESC')->get();
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
}
