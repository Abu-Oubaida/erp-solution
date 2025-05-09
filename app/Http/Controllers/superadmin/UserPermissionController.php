<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\PermissionUserHistory;
use App\Models\User;
use App\Traits\DeleteFileTrait;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Log;

class UserPermissionController extends Controller
{
    use DeleteFileTrait, ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    public function addPermission(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'user_id' => ['required','string','exists:users,id'],
                    'company_id' => ['required','string',function ($attribute, $value, $fail) {
                        $existsInPermissions = DB::table('user_company_permissions')
                            ->where('company_id', $value)
                            ->exists();

                        $existsInUsers = DB::table('users')
                            ->where('company', $value)
                            ->exists();

                        if (!$existsInPermissions && !$existsInUsers) {
                            $fail('The selected company_id is invalid.');
                        }
                    }],
                    'parentPermission' => ['required','string',function ($attribute, $value, $fail) {
                        if ($value != 0) {
                            // Apply the exists rule only when pid is not 0
                            $exists = DB::table('company_module_permissions')
                                ->where('module_parent_id', $value)
                                ->exists();
                            if (!$exists) {
                                $fail('The selected pid is invalid.');
                            }
                        }
                    }],
                    'childPermission.*' => ['required','string','exists:company_module_permissions,module_id'],
                ]);
                extract($request->post());
                $user = User::findOrFail($user_id);
                //child permission
                if (count($childPermission))
                {
                    $permissions = Permission::whereIn('id', $childPermission)->get();
                    foreach ($permissions as $permission)
                    {
                        if (!PermissionUser::where('user_id', $user->id)->where('company_id',$company_id)->where('permission_name', $permission->name)->where('parent_id',$permission->parent_id)->exists())
                        {
                            $create = PermissionUser::create([
                                'company_id' => $company_id,
                                'user_id' => $user->id,
                                'permission_name' => $permission->name,
                                'parent_id' => $permission->parent_id,
                            ]);
                            if ($create->id)
                            {
                                PermissionUserHistory::create([
                                    'company_id' => $company_id,
                                    'admin_id'=>$this->user->id,
                                    'user_id'=>$user->id,
                                    'permission_id'=>$create->id,
                                    'operation_name'=> 'added',
                                ]);
                            }
                        }
                    }
                    return back()->with('success','Permission added successfully.');
                }
                return back()->with('error','No options are selected');
            }
            return back()->withInput();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }

    }

    public function removePermission(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
        ]);
        try {
            extract($request->post());

            $pid = Crypt::decryptString($id);
            $uid = Crypt::decryptString($user_id);
            $user = User::findOrFail($uid);
            // Remove the permission from the user
            $userPermissionThis = $user->permissions()->where('id', $pid)->first();
            PermissionUserHistory::create([
                'company_id' => $userPermissionThis->company_id,
                'admin_id'=>Auth::user()->id,
                'user_id'=>$user->id,
                'permission_id'=>$pid,
                'operation_name'=> 'deleted',
            ]);
            $userPermissionThis->delete();
            return back()->with('success','Permission removed successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function removeMultiplePermission(Request $request)
    {
        $pids = $request->selected;
        $uid = $request->userID;
        $user = User::findOrFail($uid);
         try {
            // Fetch all the permissions associated with the selected $pids
            $userPermissions = $user->permissions()->whereIn('id', $pids);
            if ($userPermissions->get()->isNotEmpty()) {
                // Prepare an array of history records to insert
                $historyData = [];
                foreach ($userPermissions->get() as $userPermission) {
                    $historyData[] = [
                        'company_id' => $userPermission->company_id,
                        'admin_id' => Auth::id(),
                        'user_id' => $user->id,
                        'permission_id' => $userPermission->id,
                        'operation_name' => 'deleted'
                    ];
                }
        
                // Bulk insert the history records into PermissionUserHistory
                PermissionUserHistory::insert($historyData);
        
                // Perform a bulk delete on all fetched permissions in a single query
                $userPermissions->delete();
        
                // Return a successful JSON response
                return response()->json([
                    'status' => 'success',
                    'message' => 'Permissions removed successfully.'
                ], 200);
            } else {
                // Return error if no permissions found
                return response()->json([
                    'status' => 'error',
                    'message' => 'No permissions found to delete.'
                ], 400);
            }
        } catch (\Throwable $exception) {
            // Handle the exception and provide an error message  
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting permissions. Please try again later.',
                'error' => $exception->getMessage(),
            ], 500);
        }

}
}