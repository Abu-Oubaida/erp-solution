<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Mail\ShareVoucherDocument;
use App\Models\DocumentShareLinkEmail;
use App\Models\Permission;
use App\Models\User;
use App\Models\VoucherDocument;
use App\Models\VoucherDocumentShareEmailLink;
use App\Models\VoucherDocumentShareEmailList;
use App\Models\VoucherType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ajaxRequestController extends Controller
{
    //
    public function findPermissionChild(Request $request)
    {
        try {
            extract($request->post());
            $results = Permission::where('parent_id',$pid)->get();
            return array(
                'results' => $results
            );
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
            $shareData =VoucherDocumentShareEmailLink::with('ShareEmails')->where('share_document_id',$id)->get();
//            echo json_encode(array(
//                'results' => $shareData
//            ));
            return view('back-end/account-voucher/_share_document_model',compact('results','userEmails','shareData'));
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

    private function generateUniqueId($length = 12) {
    $randomString = Str::random($length - 8); // Generate a random string
    $timestamp = now()->timestamp; // Get the current timestamp

    // Combine the random string and the timestamp
    $uniqueId = $randomString . $timestamp;

    // Ensure the ID is exactly 12 characters long
    $uniqueId = substr($uniqueId, 0, $length);

    return $uniqueId;
}
    public function shareVoucherDocumentEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tags.*' => 'required|email',
            'refId' => 'required','string',
            'message' => 'sometimes','nullable','string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        DB::beginTransaction();
        try {
            extract($request->post());
            $id = Crypt::decryptString($refId);
            $document = VoucherDocument::with('accountVoucherInfo','accountVoucherInfo.VoucherType')->find($id);
            if ($document)
            {
                $share_id = $this->generateUniqueId();
                $shareLink = route('voucher.document.view',['document'=>Crypt::encryptString($id),'share'=>$share_id]);
                $insert1 = DB::table('voucher_document_share_email_links')->insertGetId([
                    'share_id'  =>  $share_id,
                    'share_document_id' =>  $id,
                    'status'    =>  1,
                    'shared_by' =>  Auth::user()->id,
                ]);
                if (!$insert1) {
                    // Rollback the transaction if the second insert for any item failed
                    DB::rollBack();
                    echo json_encode(array(
                        'error' => array(
                            'msg' => 'Failed to execute the insert.',
                            'code' => '126',
                        )
                    ));
                }
                else{
                    foreach ($tags as $email)
                    {
                        $insert2 = DB::table('voucher_document_share_email_lists')->insert([
                            'share_id'  =>  $insert1,
                            'email'   =>  $email,
                        ]);
                        if (!$insert2) {
                            // Rollback the transaction if the second insert for any item failed
                            DB::rollBack();
                            echo json_encode(array(
                                'error' => array(
                                    'msg' => 'Failed to execute the insert.',
                                    'code' => '126',
                                )
                            ));
                        }
                    }
                    DB::commit();
                }
                if ($tags && (is_array($tags) || is_object($tags))) {
                    Mail::to($tags)->send(new ShareVoucherDocument($shareLink, $message));
                    // Email sent successfully
                } else {
                    // Handle the case where $tags is null or not iterable
                    // For example, you can log an error or return a response indicating an issue with the recipient list.
                    return response()->json(['error' => 'Invalid recipient list'], 400);
                }
                echo json_encode(array(
                    'results' => 'Document sent to email successfully!'
                ));
            }
            else {
                echo json_encode(array(
                    'error' => array(
                        'msg' => 'Document Not Found!',
                        'code' => '404',
                    )
                ));
            }
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

    public function emailLinkStatusChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ref' => 'required','string',
            'status' => 'required','string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        try {
            extract($request->post());
            $id = Crypt::decryptString($ref);
            $status = Crypt::decryptString($status);
            if ($status == 0)
            {
                $newStatus = 1;
            }
            else{
                $newStatus = 0;
            }
            if (VoucherDocumentShareEmailLink::find($id))
            {
                VoucherDocumentShareEmailLink::where('id',$id)->update([
                    'status'=>$newStatus,
                ]);
                echo json_encode(array(
                    'results' => 'Data update successfully!'
                ));

            }else{
                echo json_encode(array(
                    'error' => array(
                        'msg' => 'Not Found!',
                        'code' => '404',
                    )
                ));
            }
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
