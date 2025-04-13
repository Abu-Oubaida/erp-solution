<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\User;
use App\Models\UserCompanyPermission;
use App\Models\userProjectPermission;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ControlPanelController extends Controller
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
        return view('back-end/control-panel/index');
    }

    public function userProjectPermission()
    {
        try {
            $companies = $this->getCompany()->get();
//            $employees = User::with(['getDepartment', 'getDesignation','roles'])->where('status', 1)->where('company_id', $this->user->company_id)->get();
            return view('back-end.control-panel.user-project-permission', compact('companies'));
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }
    public function companyWiseUser(Request $request)
    {
        try {
            $permision = $this->permissions()->control_panel;
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $users = $this->companyWisePermissionUsers($company_id,$permision)->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $users,
                    'message' => 'Request processed successfully.'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=> 'Request method not allowed!',
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
    public function userProjectPermissionSearch(Request $request)
    {
        try {
            $permision = $this->permissions()->add_user_project_permission;
            $request->validate([
                'user'  => [ 'required', 'string', 'exists:users,id' ],
                'company_id' => ['required','string','exists:company_infos,id'],
            ]);
            if ($request->isMethod('post')) {
                extract($request->post());
                $user = $request->post('user');

                $selectedUserPermissionCompany = UserCompanyPermission::where('user_id',$user)->where('company_id',$company_id)->first();
                $selectedUserCompany = $this->getUserAll()->where('id',$user)->where('company',$company_id)->first();
                if ((isset($selectedUserCompany) && (($selectedUserCompany->roles->first()->name == 'systemsuperadmin' || $selectedUserCompany->roles->first()->name == 'superadmin'))) || (isset($selectedUserPermissionCompany) && (($selectedUserPermissionCompany->userRole->name == 'systemsuperadmin' || $selectedUserPermissionCompany->userRole->name == 'superadmin')))) {
                    return response()->json([
                        'status'=>'error',
                        'message'=>'Selected user is System Super Admin or Super Admin. No need to assigned any permission',
                    ]);
                }
                $projects = branch::where('status', 1)->where('company_id', $company_id)->get();
                $userProjectPermissions = userProjectPermission::with(['user','projects','company'])->where('company_id', $company_id)->where('user_id', $user)->get();
                $userIDs = userProjectPermission::where('company_id', $company_id)
                    ->where('user_id','!=',$user)
                    ->pluck('user_id')->unique()->toArray();
                $permission_users = $this->getUser($permision)->whereIn('id',$userIDs)->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'systemsuperadmin');
                })->get();
                $view = view('back-end.control-panel._user-project-permission-add',compact('projects','userProjectPermissions','user','permission_users','company_id'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Request processed successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Requested method not allowed.',
            ],200);

        }catch (\Throwable $exception){
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],200);
        }
    }

    public function userProjectPermissionCopy(Request $request)
    {
        try {
            $request->validate([
                'copy_user_id'  => [ 'required', 'string', 'exists:user_project_permissions,user_id' ],
                'user_id'  => [ 'required', 'string', 'exists:users,id'],
                'company_id' => ['required','string','exists:company_infos,id'],
            ]);
            if ($request->isMethod('post')) {
                extract($request->post());
                $copy_user_permissions = userProjectPermission::where('user_id', $copy_user_id)->get();
                if (count($copy_user_permissions))
                {
                    $user_old_permission = userProjectPermission::where('user_id',$user_id)->where('company_id',$company_id)->get();
                    foreach ($user_old_permission as $uop)
                    {
                        $this->deleteProjectPermission($uop->id);
                    }
                    foreach ($copy_user_permissions as $cup)
                    {
                        userProjectPermission::create([
                            'date'  => now(),
                            'user_id' => $user_id,
                            'project_id' => $cup->project_id,
                            'company_id' => $company_id,
                            'created_by'    =>  $this->user->user_id,
                        ]);
                    }
                    $userProjectPermissions = userProjectPermission::with(['user','projects'])->where('company_id', $company_id)->where('user_id', $user_id)->get();
                    $view = view('back-end.control-panel.__user-permission-list',compact('userProjectPermissions'))->render();
                    return \response()->json([
                        'status'=>'success',
                        'data'=>$view,
                        'message'=>"Request process successfully!",
                    ],200);
                }
                else{
                    return \response()->json([
                        'status'=>'error',
                        'message'=>'Not Found!',
                    ],200);
                }
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Requested method not allowed.',
            ],200);
        }catch (\Throwable $exception){

            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],200);
        }
    }
    public function userProjectPermissionAdd(Request $request)
    {
        try {
            $request->validate([
                'user_id'  => [ 'required', 'string', 'exists:users,id' ],
                'project_id'  => [ 'required', 'array'],
                'project_id.*'  => [ 'required', 'string', 'exists:branches,id' ],
                'company_id'  => [ 'required', 'integer', 'exists:company_infos,id' ],
            ]);
            if ($request->isMethod('post')) {
                extract($request->post());
//                dd($project_id);
                $message = null;
                $alreadyExists = 0;
                foreach ($project_id as $key => $value) {
                    $project_permission = userProjectPermission::where('user_id', $user_id)->where('project_id', $value)->where('company_id', $this->user->company_id)->first();
                    if ($project_permission)
                    {
                        $message = ++$alreadyExists." Projects Permission Already Exists. Without that all are added successfully!";
                    }
                    else{
                        userProjectPermission::create([
                            'date'  => now(),
                            'user_id' => $user_id,
                            'project_id' => $value,
                            'company_id' => $company_id,
                            'created_by'    =>  $this->user->user_id,
                        ]);
                    }
                }
                return $this->userProjectPermissionAddReturn($user_id,$message,$company_id);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Requested method not allowed.',
            ],200);

        }catch (\Throwable $exception){
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],200);
        }
    }
    private function userProjectPermissionAddReturn($user_id,$message,$company_id)
    {
        $userProjectPermissions = userProjectPermission::with(['user','projects'])->where('company_id', $company_id)->where('user_id', $user_id)->get();
        $view = view('back-end.control-panel.__user-permission-list',compact('userProjectPermissions'))->render();
        if ($message)
        {
            $msg = $message;
        }
        else
        {
            $msg = "Request processed successfully.";
        }
        return \response()->json([
            'status'=>'success',
            'data'=>$view,
            'message'=>$msg,
        ],200);
    }
    public function userProjectPermissionAddAll(Request $request)
    {
        try {
            $request->validate([
                'user_id'  => [ 'required', 'string', 'exists:users,id' ],
                'company_id'  => [ 'required', 'integer', 'exists:company_infos,id' ],
            ]);
            if ($request->isMethod('post')) {
                extract($request->post());
                $projects = branch::where('status', 1)->where('company_id', $company_id)->get();
                $message = null;
                $alreadyExists = 0;
                foreach ($projects as $p) {
                    if (userProjectPermission::where('user_id', $user_id)->where('project_id', $p->id)->where('company_id', $this->user->company_id)->first())
                    {
                        $message = ++$alreadyExists." Projects Permission Already Exists. Without that all are added successfully!";
                    }
                    else{
                        userProjectPermission::create([
                            'date'  => now(),
                            'user_id' => $user_id,
                            'project_id' => $p->id,
                            'company_id' => $company_id,
                            'created_by'    =>  $this->user->user_id,
                        ]);
                    }
                }
                return $this->userProjectPermissionAddReturn($user_id,$message,$company_id);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Requested method not allowed.',
            ],200);

        }catch (\Throwable $exception){
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],200);
        }
    }
    public function userProjectPermissionDelete(Request $request)
    {
        try {
            $request->validate([
                'value' => ['required', 'string','exists:user_project_permissions,id'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $user_id = $this->deleteProjectPermission($value);
                if ($user_id)
                {
                    $userProjectPermissions = userProjectPermission::with(['user','projects'])->where('company_id', $this->user->company_id)->where('user_id', $user_id)->get();
                    $view = view('back-end.control-panel.__user-permission-list',compact('userProjectPermissions'))->render();
                    return \response()->json([
                        'status'=>'success',
                        'data'=>$view,
                        'message'=>"Data delete successfully!",
                    ],200);
                }
                return \response()->json([
                    'status'=>'error',
                    'message'=> $user_id->getMessage(),
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Requested method not allowed.',
            ],200);
        }catch (\Throwable $exception){
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],200);
        }
    }
    public function userProjectPermissionDeleteAll(Request $request)
    {
        try {
            $request->validate([
                'user_id' => ['required', 'string','exists:user_project_permissions,user_id'],
                'company_id'  => [ 'required', 'integer', 'exists:company_infos,id' ],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $user_old_permission = userProjectPermission::where('user_id',$user_id)->where('company_id',$company_id)->get();
                if (count($user_old_permission))
                {
                    foreach ($user_old_permission as $value) {
                        $this->deleteProjectPermission($value->id);
                    }
                }
                $userProjectPermissions = userProjectPermission::with(['user','projects'])->where('company_id', $company_id)->where('user_id', $user_id)->get();
                $view = view('back-end.control-panel.__user-permission-list',compact('userProjectPermissions'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>"All data delete successfully!",
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Requested method not allowed.',
            ],200);
        }catch (\Throwable $exception){
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],200);
        }
    }
    private function deleteProjectPermission($id)
    {
        try {
            $delete_data = userProjectPermission::where('id',$id);
            if ($d = $delete_data->first())
            {
                $user_id = $d->user_id;
                DB::table('user_project_permissions_deleted_histories')->insert([
                    'old_id' => $d->id,
                    'date'   => $d->date,
                    'user_id'=> $d->user_id,
                    'project_id'=> $d->project_id,
                    'company_id'=> $d->company_id,
                    'created_by'=> $d->created_by,
                    'updated_by'=> $d->updated_by,
                    'old_created_at'=> $d->created_at,
                    'old_updated_at'=> $d->updated_at,
                    'deleted_by' => $this->user->id,
                    'created_at' => now(),
                ]);
            }
            $delete_data->delete();
            return $user_id;
        }catch (\Throwable $exception){
            return $exception;
        }

    }
}
