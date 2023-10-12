<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionUserHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UserPermissionController extends Controller
{
    public function addPermission(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'user_id' => ['required','exists:users,id'],
                    'parentPermission' => ['required','string','exists:permissions,id'],
                    'childPermission.*' => ['required','string','exists:permissions,name'],
                ]);
                extract($request->post());
                $user = User::findOrFail($user_id);
//                dd()
//                //parent permission
//                if (Permission::where('id',$parentPermission)->where('name','none')->first())
//                {
//                    return back()->with('warning','No need to add None Permission')->withInput();
//                }
//                $permissionParent = Permission::where('parent_id',null)->where('id',$parentPermission)->first();
//                if (!$permissionParent)
//                {
//                    return back()->with('error','Parent Permission Not Found!')->withInput();
//                }
//                // Check if the permission already exists for the user
//                $existingPermissionParent = $user->permissions()->where('permission_name', $permissionParent->name)->first();
//                if (!$existingPermissionParent)
//                {
//                    $id1 = $user->permissions()->create([
//                        'permission_name' => $permissionParent->name,
//                        'is_parent' => 1,
//                    ])->id;
//                    PermissionUserHistory::create([
//                        'admin_id'=>Auth::user()->id,
//                        'user_id'=>$user->id,
//                        'permission_parent_id'=>$id1,
//                        'operation_name'=> 'added',
//                    ]);
//                }
                //child permission
                if (count($childPermission) != 0)
                {
                    $error = 0;
                    $historyError = 0;
                    $success = 0;
                    foreach ($childPermission as $data)
                    {
                        if (!($data == 'none'))
                        {
                            $permissionChild = Permission::where('parent_id',$parentPermission)->where('name',$data)->first();
                            if (!$permissionChild)
                            {
                                return back()->with('error','Invalid child permission ('.$data.')');
                            }
                            // Check if the permission already exists for the user
                            $existingPermissionChild = $user->permissions()->where('permission_name', $permissionChild->name)->first();
                            if (!$existingPermissionChild)
                            {
                                $create = $user->permissions()->create([
                                    'permission_name' => $permissionChild->name,
                                    'parent_id' => $parentPermission,
                                ]);
                                if ($create->id)
                                {
                                    $h = PermissionUserHistory::create([
                                        'admin_id'=>Auth::user()->id,
                                        'user_id'=>$user->id,
                                        'permission_id'=>$create->id,
                                        'operation_name'=> 'added',
                                    ]);
                                   ($h)? $success++ : $historyError++;
                                }else{
                                    $error++;
                                }
                            }
                        }
                        else{
                            $permission = Permission::find($parentPermission);
                            if (!$permission)
                            {
                                return back()->with('error','Invalid parent permission ('.$data.')');
                            }
                            $existingPermissionParent = $user->permissions()->where('permission_name', $permission->name)->first();
                            if (!$existingPermissionParent)
                            {
                                $create = $user->permissions()->create([
                                    'permission_name' => $permission->name,
                                    'parent_id' => $parentPermission,
                                ]);
                                if ($create->id)
                                {
                                    $h = PermissionUserHistory::create([
                                        'admin_id'=>Auth::user()->id,
                                        'user_id'=>$user->id,
                                        'permission_id'=>$create->id,
                                        'operation_name'=> 'added',
                                    ]);
                                    ($h)? $success++ : $historyError++;
                                }else{
                                    $error++;
                                }
                            }
                        }
                    }
                    if ($success > $error+$historyError)
                        return back()->with('success','Maximum number of permission added successfully! Please verify on list');
                    elseif ($success < $error+$historyError)
                        return back()->with('error','Maximum number of permission added not possible! Please verify on list');
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
            $user->permissions()->where('id', $pid)->delete();
            PermissionUserHistory::create([
                'admin_id'=>Auth::user()->id,
                'user_id'=>$user->id,
                'permission_id'=>$pid,
                'operation_name'=> 'deleted',
            ]);
            return back()->with('success','Permission removed successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
