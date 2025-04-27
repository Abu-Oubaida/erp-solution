<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Mail\ShareArchive;
use App\Mail\ShareVoucherDocument;
use App\Models\Account_voucher;
use App\Models\branch;
use App\Models\company_info;
use App\Models\CompanyModulePermission;
use App\Models\DocumentShareLinkEmail;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompanyPermission;
use App\Models\userProjectPermission;
use App\Models\Voucher_share_email_link;
use App\Models\Voucher_share_email_list;
use App\Models\VoucherDocument;
use App\Models\VoucherDocumentShareEmailLink;
use App\Models\VoucherDocumentShareEmailList;
use App\Models\VoucherDocumentShareLink;
use App\Models\VoucherType;
use Complex\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Symfony\Component\String\s;
use App\Traits\ParentTraitCompanyWise;
use Log;

class ajaxRequestController extends Controller
{
    use ParentTraitCompanyWise;
    public function companyCheckSet(Request $request)
    {
        try {
            $request->validate([
                'username'=> ['required','string'],
                'password'=> ['required','string']
            ]);
            extract($request->post());
            $users = User::where('email',$username)->orWhere('phone',$username)->get();

            // Filter out users with correct password
            $matchingUsers = $users->filter(function ($user) use ($request) {
                return Hash::check($request->password, $user->password);
            });

            if ($matchingUsers->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ]);
            }
            if (@$matchingUsers->first()->isSystemSuperAdmin())
            {
                $companies = company_info::where('status',1)->get(['id','company_name']);;
            }
            else {
                $userComPerIDs = [];
                $userCompanyPermissions = UserCompanyPermission::where('user_id',$matchingUsers->first()->id)->get();
                if (count($userCompanyPermissions) > 0) {
                    $userComPerIDs = $userCompanyPermissions->pluck('company_id')->unique()->toArray();
                }
                $userComPerIDs[] = (integer)$matchingUsers->first()->company;
                $companies = company_info::where('status',1)->whereIn('id',$userComPerIDs)->get(['id','company_name']);
            }
            // Return the list of companies to the frontend
            return response()->json([
                'status' => 'success',
                'message' => 'Credentials are correct',
                'companies' => $companies
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage(),
            ]);
        }
    }
    //
    public function findPermissionChild(Request $request)
    {
        try {
            if (request()->ajax()) {
                $request->validate([
                    'pids'=> ['required','array'],
                    'pids.*'=> ['required','string',function ($attribute, $value, $fail) {
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
                    'company_id'=> ['required','string',function ($attribute, $value, $fail) {
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
                ]);
                extract($request->post());
                $companyModulePermissionChild = CompanyModulePermission::select('module_id')->where('company_id',$company_id)->whereIn('module_parent_id',$pids)->orWhereIn('module_id',$pids)->get();
//                dd($companyModulePermissionChild);
//                if ($pid == 0)
//                {
//                    $companyModulePermissionChild = CompanyModulePermission::select('module_id')->where('company_id',$company_id)->get();
//                }
//                else {
//                    $companyModulePermissionChild = CompanyModulePermission::select('module_id')->where('company_id',$company_id)->where('module_parent_id',$pid)->get();
//                }
                $results = Permission::whereIn('id',$companyModulePermissionChild)->get();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Permissions found',
                    'data' => $results
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Requested method not allowed'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage(),
            ]);
        }
    }

    public function companyChangeModulePermission(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $request->validate([
                    'uid'=> ['required','string','exists:users,id'],
                    'cid'=> ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $user = User::where('id',$uid)->first();
                if ($user->company == $cid)
                {
                    $userRole = $user->roles->first();
                }
                else {
                    $userRole = UserCompanyPermission::with(['userRole'])->where('user_id',$uid)->where('company_id',$cid)->first()->userRole;
                }
                if ($userRole->name == 'superadmin')
                {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Selected user ($user->name) is a Super Admin for selected company, No need to add any permission."
                    ]);
                }
                $companyWiseParentPermission = CompanyModulePermission::select('module_parent_id')->where('company_id',$cid)->distinct()->get();
                $permissionParents = Permission::whereIn('id',$companyWiseParentPermission)->where('parent_id',null)->get();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Permission successfully changed',
                    'data' => $permissionParents
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function findArchiveDocument(Request $request)
    {
        try {
            if (request()->ajax()) {
                $validateData = $request->validate([
                    'id'=> ['required','string'],
                    'ref_id'=> ['required','string'],
                ]);
                extract($validateData);
                $id = Crypt::decryptString($id);
                $document = VoucherDocument::with(['accountVoucherInfo','accountVoucherInfo.VoucherType'])->find($id);
                $relatedDocument = Account_voucher::with(['voucherDocuments'])->where('id',$ref_id)->first();
                $root_ref = $relatedDocument->voucher_number;
                $relatedDocument_ids = $relatedDocument->voucherDocuments->pluck('id')->toArray();
                $this_document_index = array_search($id,$relatedDocument_ids);
                $array_count = count($relatedDocument_ids);
                $previous_document_id = null;
                $next_document_id = null;
                if ($array_count > 0 && $this_document_index > 0)
                {
                    $previous_document_id = $relatedDocument_ids[$this_document_index-1];
                    if ($this_document_index < $array_count-1)
                    {
                        $next_document_id = $relatedDocument_ids[$this_document_index+1];
                    }
                }
                elseif ($array_count > 0 && $this_document_index == 0)
                {
                    $next_document_id = $relatedDocument_ids[$this_document_index+1];
                }
                $view = view('back-end.archive._single-view-modal',compact('document','previous_document_id','next_document_id','root_ref'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => $view,
                    'message' => 'Document found'
                ]);
            }
            throw new \Exception('Invalid request');
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function fiendArchiveDocumentInfo(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($id);
            $result = VoucherDocument::select(['id','document'])->find($id);
            $userEmails = User::all(['name','email']);
            $shareData =VoucherDocumentShareEmailLink::with('ShareEmails','sharedBy')->where('share_document_id',$id)
            ->with(['sharedBy'=>function ($query){
                $query->select('id','name');
            }]);
            if( !Auth::user()->isSystemSuperAdmin())
            {
                if (!Auth::user()->companyWiseRoleName() == 'superadmin')
                {
                    $shareData->where('shared_by',Auth::user()->id)->where('company_id',Auth::user()->company);
                }
                else {
                    $shareData->where('company_id',Auth::user()->company);
                }
            }
            $shareData = $shareData->get();
            return view('back-end/archive/_share_document_model',compact('result','userEmails','shareData'));
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
    public function shareArchiveFiend(Request $request)
    {
        try {
            extract($request->post());
            $id = Crypt::decryptString($id);
//            $results = VoucherDocument::with(['accountVoucherInfo','accountVoucherInfo.VoucherType'])->find($id);
            $result = Account_voucher::with(['VoucherType'])->select(['id','voucher_date','voucher_number','voucher_type_id'])->find($id);
            $userEmails = User::all(['name','email']);
            $shareData =Voucher_share_email_link::with('ShareEmails','sharedBy')->where('share_voucher_id',$id)
            ->with(['sharedBy'=>function ($query){
                $query->select('id','name');
            }]);
            if( !Auth::user()->isSystemSuperAdmin())
            {
                if (!Auth::user()->companyWiseRoleName() == 'superadmin')
                {
                    $shareData->where('shared_by',Auth::user()->id)->where('company_id',Auth::user()->company);
                }
                else {
                    $shareData->where('company_id',Auth::user()->company);
                }
            }
            $shareData = $shareData->get();
            $view = view('back-end/archive/_share_archive_model',compact('result','userEmails','shareData'))->render();
            return response()->json([
                'status' => 'success',
                'message'=> 'Request process successfully!',
                'data'=> $view,
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message'=> $exception->getMessage(),
                'code'=> $exception->getCode(),
            ]);
        }
    }

    public function shareArchiveFiendMultiple(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => ['array','required'],
                'ids.*' => ['integer','required','exists:account_voucher_infos,id'],
            ]);
            extract($request->post());
//            $results = VoucherDocument::with(['accountVoucherInfo','accountVoucherInfo.VoucherType'])->find($id);
            $voucherInfos = Account_voucher::with(['VoucherType'])->select(['id','voucher_date','voucher_number','voucher_type_id'])->whereIn('id',$ids)->get();
            $userEmails = User::all(['name','email']);

            $shareDatum =Voucher_share_email_link::with(['ShareEmails','shareArchive','shareEmailByShareId','sharedBy'])
            ->select(['id','company_id','share_id','share_voucher_id','status','shared_by'])
            ->with(['sharedBy'=>function($query){
                $query->select('users.id','users.name');
            }])
            ->with(['ShareEmails'=>function($query){
                $query->select('share_link_id','email');
            }])
            ->with(['shareEmailByShareId'=>function($query){
                $query->select('share_id','share_link_id','email');
            }])
            ->whereIn('share_voucher_id',$ids);
            // dd(Auth::user()->isSystemSuperAdmin());
            if( !Auth::user()->isSystemSuperAdmin())
            {
                if (!Auth::user()->companyWiseRoleName() == 'superadmin')
                {
                    $shareDatum->where('shared_by',Auth::user()->id)->where('company_id',Auth::user()->company);
                }
                else {
                    $shareDatum->where('company_id',Auth::user()->company);
                }
            }
            $shareDatum = $shareDatum->get();
            $view = view('back-end/archive/_share_archive_model_multiple',compact('voucherInfos','userEmails','shareDatum'))->render();
            return response()->json([
                'status' => 'success',
                'message'=> 'Request process successfully!',
                'data'=> $view,
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message'=> $exception->getMessage(),
                'code'=> $exception->getCode(),
            ]);
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
    public function shareArchiveDocumentEmail(Request $request)
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
                $shareLink = route('archive.document.view',['document'=>Crypt::encryptString($id),'share'=>$share_id]);
                $insert1 = DB::table('voucher_document_share_email_links')->insertGetId([
                    'company_id' => Auth::user()->company_id,
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
                            'company_id' => Auth::user()->company_id,
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
                    Mail::to($tags)->send(new ShareVoucherDocument($shareLink, null));
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
    public function shareArchiveEmail(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'tags.*' => 'required|email',
                'refId' => 'required','string',
                'message' => 'sometimes','nullable','string',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            extract($request->post());
            $id = Crypt::decryptString($refId);//account_voucher_infos_id
            $document = Account_voucher::with('VoucherType','VoucherDocument')->find($id);
            if ($document)
            {
                $share_id = $this->generateUniqueId();
                $shareLink = route('archive.view',['archive'=>Crypt::encryptString($id),'share'=>$share_id]);
                $insert1 = DB::table('voucher_share_email_links')->insertGetId([
                    'company_id' => Auth::user()->company_id,
                    'share_id'  =>  $share_id,
                    'share_voucher_id' =>  $id,
                    'status'    =>  1,
                    'shared_by' =>  Auth::user()->id,
                ]);
                if (!$insert1) {
                    // Rollback the transaction if the second insert for any item failed
                    throw  new \Exception('Failed to execute the insert.');
                }
                else{
                    $insertData = collect($tags)->map(function ($email) use ($insert1) {
                        return [
                            'company_id' => Auth::user()->company_id,
                            'share_link_id'  =>  $insert1,
                            'email'   =>  $email,
                        ];
                    })->toArray();
                    $insert2 = DB::table('voucher_share_email_lists')->insert($insertData);
                    if (!$insert2) {
                        // Rollback the transaction if the second insert for any item failed
                        throw  new \Exception('Failed to execute the insert.');
                    }
                    DB::commit();
                }
                if ($tags && (is_array($tags) || is_object($tags))) {
                    Mail::to($tags)->send(new ShareArchive($shareLink, null));
                    // Email sent successfully
                } else {
                    throw  new \Exception('Invalid recipient list');
                }
                echo json_encode(array(
                    'results' => 'Document sent to email successfully!'
                ));
            }
            else {
                throw  new \Exception('Document Not Found!');
            }
        }catch (\Throwable $exception)
        {
            DB::rollBack();
            echo json_encode(array(
                'error' => array(
                    'msg' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                )
            ));
        }
    }
    public function shareArchiveEmailMultiple(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'tags.*' => ['required','email'],
                'ids' => ['required','array'],
                'ids.*' => ['integer','required','exists:account_voucher_infos,id'],
                'message' => 'sometimes','nullable','string',
            ]);
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
            extract($request->post());
            $share_id = $this->generateUniqueId();
            $shareLink = route('archive.view',['archive'=>null,'share'=>$share_id]);
            $insert1_data = [] ;
            $insert1_data = collect($ids)->map(function ($id) use ($share_id) {
                return [
                    'company_id' => Auth::user()->company_id,
                    'share_id'  =>  $share_id,
                    'share_voucher_id' =>  $id,
                    'status'    =>  1,
                    'shared_by' =>  Auth::user()->id,
                ];
            })->toArray();
            $insert1 = DB::table('voucher_share_email_links')->insert($insert1_data);

            if (!$insert1) {
                // Rollback the transaction if the second insert for any item failed
                throw  new \Exception('Failed to execute the insert.');
            }
            else{
                // Voucher_share_email_link::insert($insert1_data);
                // Voucher_share_email_list::insert($insert1_data);
                $insertData = collect($tags)->map(function ($email) use ($share_id) {
                    return [
                        'company_id' => Auth::user()->company_id,
                        'share_id'  =>  $share_id,
                        'email'   =>  $email,
                    ];
                })->toArray();
                $insert2 = DB::table('voucher_share_email_lists')->insert($insertData);
                if (!$insert2) {
                    // Rollback the transaction if the second insert for any item failed
                    throw  new \Exception('Failed to execute the insert.');
                }
                DB::commit();
            }
            if ($tags && (is_array($tags) || is_object($tags))) {
                Mail::to($tags)->send(new ShareArchive($shareLink, null));
                // Email sent successfully
            } else {
                throw  new \Exception('Invalid recipient list');
            }
            return response()->json([
                'status'=> 'success',
                'message'=> 'Mail send successfully!',
            ]);

        }catch (\Throwable $exception)
        {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message'=> $exception->getMessage(),
                'code' => $exception->getCode(),
            ]);
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
    public function archiveEmailLinkStatusChange(Request $request)
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
//            Voucher_share_email_link::find($id);
            if (Voucher_share_email_link::find($id))
            {
                Voucher_share_email_link::where('id',$id)->update([
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
    public function archiveShareType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refId' => 'required','string',
            'value' => 'required','numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }
        try {
            extract($request->post());
            $id = Crypt::decryptString($refId);
            $link_data = VoucherDocumentShareLink::where('share_document_id',$id)->where('share_type',$value)->first();
            if (!($link_data))
            {
                $user = Auth::user();
                $share_id = $this->generateUniqueId();
                $link_data = VoucherDocumentShareLink::create([
                    'company_id'=> $user->company,
                    'share_id'=>$share_id,
                    'share_type'=>$value,
                    'share_document_id'=>$id,
                    'status'=>1,
                    'shared_by'=>$user->id,
                ]);
            }
            $shareLink = route('archive.document.view',['document'=>Crypt::encryptString($link_data->share_document_id),'share'=>$link_data->share_id]);
            return $shareLink;
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
    public function companyWiseProjects(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $projects = branch::where('company_id',$company_id)->where('status',1)->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $projects,
                    'message' => 'Request processed successfully!'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=> 'Request method not allowed!'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
    public function companyWiseDepartments(Request $request){
        try {
            $deparments = company_info::with(['departments'])->find($request->company_id)->departments->where('status',1);
            return response()->json([
                'status' => 'success',
                'data' => $deparments,
                'company_id'=>$request->company_id,
                'message' => 'Request processed successfully!'
            ]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
    public function userWiseCompanyProjectPermissions(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                    'user_id' => ['required','string','exists:users,id'],
                ]);
                extract($request->post());
                if (Auth::user()->isSystemSuperAdmin())
                {
                    $projects = branch::with(['getUsers','company'])->where('company_id',$company_id)->where('status',1)->get();
                }
                else {
                    $object = branch::with(['getUsers','company']);
                    if (Auth::user()->companyWiseRoleName() == 'superadmin')
                    {
                        $projects = $object->where('company_id',$company_id)->where('status',1)->get();
                    }
                    else
                    {
                        $projects = $object->whereIn('id',userProjectPermission::where('user_id',$user_id)->get()->pluck('project_id')->unique()->toArray())->where('company_id',$company_id)->where('status',1)->get();
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'data' => $projects,
                    'message' => 'Request processed successfully!'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=> 'Request method not allowed!'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function searchCompanyBranchUsers(Request $request)
    {
        try {

            if ($request->ajax())
            {
                $validatedData = $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                    'branches_id' => ['required','array'],
                    'branches_id.*' => ['required','string','exists:branches,id'],
                ]);
                extract($validatedData);
                $users = User::with(['department'=>function ($query) {
                    $query->select('id','dept_name');
                }])->where('company',$company_id)->whereIn('branch_id',array_values($branches_id))->where('status',1)->get(['id','dept_id','name'])->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name.' ('.$user->department->dept_name.')',
                    ];
                });
                return response()->json([
                    'status' => 'success',
                    'data' => $users,
                    'message' => 'Request processed successfully!'
                ]);
            }
            throw new \Exception("Request method not allowed!");
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
