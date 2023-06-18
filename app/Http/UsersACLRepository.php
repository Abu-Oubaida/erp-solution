<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
use App\Models\filemanager_permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersACLRepository implements ACLRepository
{

    /**
     * @inheritDoc
     */
    public function getUserID()
    {
        // TODO: Implement getUserID() method.
//        if (Auth::user()->hasRole())
        return Auth::user()->id();
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        // TODO: Implement getRules() method.
        if (Auth::user()->hasRole('superadmin'))
        {
            $dir = config('app.file_manager_url');

            $fileManagers = scandir($dir);
            $array=[
                    ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                    ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ];
            foreach ($fileManagers as $file)
            {
                array_push($array,['disks' => 'file-manager', 'path' => $file, 'access' => 2]);
                array_push($array,['disks' => 'file-manager', 'path' => $file.'/*', 'access' => 2]);
            }
            return $array;
        }elseif (Auth::user()->hasRole('admin'))
        {
            $dir = config('app.file_manager_url');
            $fileManagers = scandir($dir);
            $array=[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'admin', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'admin/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
            ];
            foreach ($fileManagers as $file)
            {
                if($permission = filemanager_permission::where("dir_name",$file)->where("status",1)->where("user_id",Auth::user()->id)->first())
                {
                    if ($permission->permission_type > 2)
                    {
                        $p = 2;
                    }else {
                        $p = $permission->permission_type;
                    }
                    array_push($array,['disks' => 'file-manager', 'path' => $file, 'access' => $p]);
                    array_push($array,['disks' => 'file-manager', 'path' => $file.'/*', 'access' => $p]);
                }
            }
            return $array;
        }elseif (Auth::user()->hasRole('user'))
        {
            $dir = config('app.file_manager_url');
            $fileManagers = scandir($dir);
            $array=[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
            ];
            foreach ($fileManagers as $file)
            {
                if($permission = filemanager_permission::where("dir_name",$file)->where("status",1)->where("user_id",Auth::user()->id)->first())
                {
                    if ($permission->permission_type > 2)
                    {
                        $p = 2;
                    }else {
                        $p = $permission->permission_type;
                    }
                    array_push($array,['disks' => 'file-manager', 'path' => $file, 'access' => $p]);
                    array_push($array,['disks' => 'file-manager', 'path' => $file.'/*', 'access' => $p]);
                }
            }
            return $array;
        }else
        {
            return[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 1],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 1],
            ];
        }
    }
}
