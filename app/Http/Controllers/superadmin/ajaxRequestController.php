<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class ajaxRequestController extends Controller
{
    //
    public function __construct()
    {
        header('Content-Type: application/json');
    }
    public function fienPermissionChild(Request $request)
    {
        try {
            extract($request->post());
            $results = Permission::where('parent_id',$pid)->get();
            echo json_encode(array(
                'results' => $results
            ));
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
