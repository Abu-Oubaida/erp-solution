<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function addPermission(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'permission_name' => 'required|string',
                ]);

                $user = User::findOrFail($request->user_id);

                // Check if the permission already exists for the user
                $existingPermission = $user->permissions()->where('permission_name', $request->permission_name)->first();

                dd($existingPermission);
                if ($existingPermission) {
                    return response()->json(['message' => 'Permission already exists for this user.'], 400);
                }

                // Add the permission to the user
                $user->permissions()->create([
                    'permission_name' => $request->permission_name,
                ]);

                return response()->json(['message' => 'Permission added successfully.'], 200);

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
            'user_id' => 'required|exists:users,id',
            'permission_name' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);

        // Remove the permission from the user
        $user->permissions()->where('permission_name', $request->permission_name)->delete();

        return response()->json(['message' => 'Permission removed successfully.'], 200);
    }
}
