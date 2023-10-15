<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Models\VoucherDocument;
use App\Models\VoucherType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ajaxRequestController extends Controller
{
    //
    public function __construct()
    {
        header('Content-Type: application/json');
    }
    public function findPermissionChild(Request $request)
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

    public function findVoucherDocument(Request $request)
    {
        try {
            extract($request->post());
            $path = Crypt::decryptString($path);
            echo $path;
//            $results = VoucherDocument::find($id);
//            if ($results)
//            {
//                echo json_encode(array(
//                    'results' => $results
//                ));
//            }
//            else{
//                echo json_encode(array(
//                    'error' => array(
//                        'msg' => "Not Found!",
//                        'code' => "404",
//                    )
//                ));
//            }
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

    public function findVoucherDocumentInfo(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($id);
            $results = VoucherDocument::with(['accountVoucherInfo','accountVoucherInfo.VoucherType'])->find($id);
            $userEmails = User::all(['name','email']);
            return view('back-end/account-voucher/_share_document_model',compact('results','userEmails'));
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

    public function shareVoucherDocument(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($refId);
            echo json_encode(array(
                'results' => $id
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
