<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
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
            return [
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'superadmin', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'superadmin/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'admin', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'admin/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 2],
            ];
        }elseif (Auth::user()->hasRole('admin'))
        {
            return[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'admin', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'admin/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
                ];
        }elseif (Auth::user()->hasRole('user'))
        {
            return[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],

            ];
        }else
        {
            return[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 1],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'superadmin', 'access' => 0],// guest don't have access for this folder
                ['disks' => 'file-manager', 'path' => 'superadmin/*', 'access' => 0],
                ['disks' => 'file-manager', 'path' => 'admin', 'access' => 1],// only read - guest can't change folder - rename, delete
                ['disks' => 'file-manager', 'path' => 'admin/*', 'access' => 1],// only read all files and foders in this folder
                ['disks' => 'file-manager', 'path' => 'user', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
                ['disks' => 'file-manager', 'path' => 'common', 'access' => 1],
                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 1],
            ];
        }
    }
}
