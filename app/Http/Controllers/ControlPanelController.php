<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\User;
use App\Models\userProjectPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ControlPanelController extends Controller
{
    private $user;
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $this->user= Auth::user();
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
            $employees = User::with(['getDepartment', 'getDesignation','roles'])->where('status', 1)->where('company_id', $this->user->company_id)->get();
            return view('back-end.control-panel.user-project-permission', compact('employees'));

        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }
    public function userProjectPermissionSearch(Request $request)
    {
        try {
            $request->validate([
                'user'  => [ 'required', 'string', 'exists:users,id' ],
            ]);
            if ($request->isMethod('post')) {
                $user = $request->post('user');
                $projects = branch::where('status', 1)->where('company_id', $this->user->company_id)->get();
                $userProjectPermissions = userProjectPermission::with(['user','projects'])->where('company_id', $this->user->company_id)->where('user_id', $user)->get();
                $view = view('back-end.control-panel._user-project-permission-add',compact('projects','userProjectPermissions','user'))->render();
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

    public function userProjectPermissionAdd(Request $request)
    {
        try {
            $request->validate([
                'user_id'  => [ 'required', 'string', 'exists:users,id' ],
                'project_id'  => [ 'required', 'string', 'exists:branches,id' ],
            ]);
            if ($request->isMethod('post')) {
                extract($request->post());
                $project_permission = userProjectPermission::where('user_id', $user_id)->where('project_id', $project_id)->where('company_id', $this->user->company_id)->first();
                if ($project_permission)
                {
                    return \response()->json([
                        'status'=>'error',
                        'message'=>'Project permission already added.'
                    ]);
                }
                userProjectPermission::create([
                    'date'  => now(),
                    'user_id' => $user_id,
                    'project_id' => $project_id,
                    'company_id' => $this->user->company_id,
                    'created_by'    =>  $this->user->user_id,
                ]);
                $userProjectPermissions = userProjectPermission::with(['user','projects'])->where('company_id', $this->user->company_id)->where('user_id', $user_id)->get();
                $view = view('back-end.control-panel.__user-permission-list',compact('userProjectPermissions'))->render();
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
}
