<?php

namespace App\Http;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
use App\Models\filemanager_permission;
use App\Models\User;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Support\Facades\Auth;

class UsersACLRepository implements ACLRepository
{
    use ParentTraitCompanyWise;

    public function __construct()
    {
        $this->user = Auth::user(); // Get the authenticated user
        $this->setUser();
    }

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
        if (Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('systemsuperadmin'))
        {
            $dir = config('app.file_manager_url');
            $fileManagers = scandir($dir);
            if (Auth::user()->hasRole('systemsuperadmin'))
            {
                $array=[
                    ['disks' => 'file-manager', 'path' => '', 'access' => 2],
                    ['disks' => 'file-manager', 'path' => '*', 'access' => 2],
                    ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                    ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
                ];
                foreach ($fileManagers as $file)
                {
                    array_push($array,['disks' => 'file-manager', 'path' => $file, 'access' => 2]);
                    array_push($array,['disks' => 'file-manager', 'path' => $file.'/*', 'access' => 2]);
                }
                return $array;
            }
            $array=[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
            ];
            $companies = $this->getCompany()->where('id',Auth::user()->company)->get();
            dd($companies);
            if (count($companies) > 0) {
                foreach ($companies as $company) {
                    array_push($array, ['disks' => 'file-manager', 'path' => $company->company_code, 'access' => 2]);
                    array_push($array, ['disks' => 'file-manager', 'path' => $company->company_code."/*", 'access' => 2]);
                }
            }
            return $array;
        }
        elseif (Auth::user())
        {
            $dir = config('app.file_manager_url');
            $array=[
                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'admin', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'admin/*', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'user', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
            ];
            $companies = $this->getCompany()->get();
            if (count($companies) > 0) {
                foreach ($companies as $company) {
                    array_push($array, ['disks' => 'file-manager', 'path' => $company->company_code, 'access' => 2]);
                    array_push($array, ['disks' => 'file-manager', 'path' => $company->company_code."/", 'access' => 2]);
                    $fileManagers = scandir($dir."/".$company->company_code);
                    foreach ($fileManagers as $file)
                    {
                        if($permission = filemanager_permission::where("dir_name",$file)->where('company_id', $company->id)->where("status",1)->where("user_id",Auth::user()->id)->first())
                        {
                            if ($permission->permission_type > 2)
                            {
                                $p = 2;
                            }else {
                                $p = $permission->permission_type;
                            }
                            array_push($array,['disks' => 'file-manager', 'path' => $company->company_code."/".$file, 'access' => $p]);
                            array_push($array,['disks' => 'file-manager', 'path' => $company->company_code."/".$file.'/*', 'access' => $p]);
                        }
                    }
                }
            }
            return $array;
        }
//        elseif (Auth::user()->hasRole('user'))
//        {
//            $dir = config('app.file_manager_url');
//            $fileManagers = scandir($dir);
//            $array=[
//                ['disks' => 'file-manager', 'path' => '/', 'access' => 2],
//                ['disks' => 'file-manager', 'path' => '/*', 'access' => 2],
////                ['disks' => 'file-manager', 'path' => 'user', 'access' => 2],
////                ['disks' => 'file-manager', 'path' => 'user/*', 'access' => 2],
////                ['disks' => 'file-manager', 'path' => 'common', 'access' => 2],
////                ['disks' => 'file-manager', 'path' => 'common/*', 'access' => 2],
////                ['disks' => 'file-manager', 'path' => 'guest', 'access' => 2],
////                ['disks' => 'file-manager', 'path' => 'guest/*', 'access' => 2],
//            ];
//            foreach ($fileManagers as $file)
//            {
//                if($permission = filemanager_permission::where("dir_name",$file)->where("status",1)->where("user_id",Auth::user()->id)->first())
//                {
//                    if ($permission->permission_type > 2)
//                    {
//                        $p = 2;
//                    }else {
//                        $p = $permission->permission_type;
//                    }
//                    array_push($array,['disks' => 'file-manager', 'path' => $file, 'access' => $p]);
//                    array_push($array,['disks' => 'file-manager', 'path' => $file.'/*', 'access' => $p]);
//                }
//            }
//            return $array;
//        }

        return[
            ['disks' => 'file-manager', 'path' => '', 'access' => 1],
            ['disks' => 'file-manager', 'path' => '/', 'access' => 1],
        ];
    }
}
