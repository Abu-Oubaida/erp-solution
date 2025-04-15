<?php

namespace App\Http\Controllers;

use App\Models\Account_voucher;
use App\Models\ArchiveInfoLinkDocument;
use App\Models\ArchiveLinkDocumentDeleteHistory;
use App\Models\branch;
use App\Models\company_info;
use App\Models\User;
use App\Models\Voucher_type_permission_user_delete_history;
use App\Models\VoucherDocument;
use App\Models\VoucherDocumentDeleteHistory;
use App\Models\VoucherDocumentIndividualDeletedHistory;
use App\Models\VoucherType;
use App\Rules\AccountVoucherInfoStatusRule;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\department;
use App\Models\Voucher_type_permission_user;
use Log;
use function PHPUnit\Framework\directoryExists;


class ArchiveController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    private $accounts_document_path = "file-manager/archive/";

    public function createArchiveType(Request $request)
    {
        // dd(round(disk_total_space($this->accounts_document_path) / (1024 ** 3), 2),round(disk_($this->accounts_document_path) / (1024 ** 3), 2));
        $permission = $this->permissions()->add_archive_data_type;
        try {
            if ($request->isMethod('post'))
            {
                return $this->storeArchiveType($request);
            }
            $voucherTypes = $this->getArchiveTypeList($permission);
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end/archive/type/add',compact('voucherTypes','companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function listArchiveDataType(Request $request){
        $permission = $this->permissions()->archive_data_type_list;
        $voucherTypes = $this->getArchiveTypeList($permission);
            return view('back-end/archive/type/list',compact('voucherTypes'))->render();
    }
    private function storeArchiveType(Request $request,$voucherTypeID=null):RedirectResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'data_type_title'   =>  ['required', 'string', 'max:255',Rule::unique('voucher_types','voucher_type_title')->where('company_id', $request->post('company'))],
                'data_type_code'    =>  ['sometimes','nullable', 'numeric', Rule::unique('voucher_types','code')->where('company_id', $request->post('company'))],
                'status'               =>  ['required', 'numeric'],
                'remarks'              =>  ['sometimes','nullable', 'string'],
                'company'               => ['sometimes', 'integer', 'exists:company_infos,id'],
                'company_departments_users'=> ['sometimes','array','exists:users,id'],
            ]);
            extract($request->post());
            $user = Auth::user();
            $voucherType = VoucherType::create([
                'company_id'=> $company,
                'status'    =>  $status,
                'voucher_type_title'=>  $data_type_title,
                'code'      =>  $data_type_code,
                'remarks'   =>  $remarks,
                'created_by'=>  $user->id,
                'updated_by'=>  $user->id,
            ]);
            if (
                !empty($company_departments_users) && is_array($company_departments_users)) {
                $insertData = collect($company_departments_users)->map(function ($department_user_id) use ($voucherType,$company, $user) {
                    return [
                        'voucher_type_id' => $voucherType->id,
                        'user_id' => $department_user_id,
                        'company_id' => $company,
                        'created_by' => $user->id,
                        'updated_by' => null,
                    ];
                })->toArray();

                if (!empty($insertData)) {
                    DB::table('voucher_type_permission_user')->insert($insertData);
                }
            }
            DB::commit();
            return back()->with('success','Data insert successfully');
        }catch (\Throwable $exception)
        {
            DB::rollBack();
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    private function dataTypeUserPermissionStore($type_company_id,array $type_ids, array $user_ids)
    {
        try {
            if (empty($type_ids) || empty($user_ids) || empty($type_company_id)) {
                throw new \Exception('Empty parameter error');
            }
            $insertData = [];
            $alreadyHasPermission = [];
            foreach ($type_ids as $type_id) {
                foreach ($user_ids as $user_id) {
                    if (!Voucher_type_permission_user::where('company_id', $type_company_id)->where('user_id', $user_id)->where('voucher_type_id',$type_id)->exists()) {
                        $insertData[] = [
                            'company_id' => $type_company_id,
                            'voucher_type_id' => $type_id,
                            'user_id' => $user_id,
                            'created_by' => $this->user->id,
                            'created_at' => now(),
                            'updated_at' => NULL,
                        ];
                    }
                    else{
                        $alreadyHasPermission[] = [
                            'company_id' => $type_company_id,
                            'voucher_type_id' => $type_id,
                            'user_id' => $user_id,
                        ];
                    }
                }
            }
            if (!empty($insertData)) {
                if (Voucher_type_permission_user::insert($insertData)) {
                    return [
                        'status' => true,
                        'message' => 'Inserted successfully',
                        'already_exists' => $alreadyHasPermission,
                    ];
                }
                throw new \Exception('Data insertion error. Try again');
            }
            // All were duplicates
            return [
                'status' => false,
                'message' => 'All selected permissions already exist.',
                'already_exists' => $alreadyHasPermission,
            ];
        }catch (\Throwable $exception)
        {
            return response()->exception;
        }

    }

    public function archiveDataTypeUserPermissionAdd(Request $request)
    {
        try {
            if ($request->ajax())
            {
                $validatedData = $request->validate([
                    'type_company_id' => ['required','integer','exists:company_infos,id'],
                    'data_types' => ['required','array'],
                    'data_types.*' => ['integer', 'exists:voucher_types,id'],
                    'permission_users' => ['required', 'array'],
                    'permission_users.*' => ['integer', 'exists:users,id'],
                ]);
                extract($validatedData);
                $result = $this->dataTypeUserPermissionStore($type_company_id, $data_types, $permission_users);

                if ($result['status']) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $result['message']. (count($result['already_exists'])? " And ".count($result['already_exists'])." items have been archived.":''),
                    ]);
                }

                // No new insert, all already existed
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message'],
                ]);
            }
            throw new \Exception('Requested method not allowed!');
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }
    public function editArchiveType(Request $request, $voucherTypeID)
    {
        try {
            $permission = $this->permissions()->edit_archive_data_type;
            if ($request->isMethod('put'))
            {
                return $this->updateArchiveType($request,$voucherTypeID);
            }
            $vtID = Crypt::decryptString($voucherTypeID);
            $voucherType = VoucherType::find($vtID);
            $voucherTypes = $this->getArchiveTypeList($permission);
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end/archive/type/edit',compact('voucherType','voucherTypes','companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function getArchiveTypeList($permission)
    {
        return $this->archiveTypeList($permission)->get();
    }
    public function archiveDataListPermissionWithUsers(Request $request){
        try{
            if ($request->isMethod('post')){
                if($request->encrypt_voucher_id){
                    $voucherId = Crypt::decryptString($request->id);
                }else{
                    $voucherId = $request->id;
                }

                $usersWithPermission = VoucherType::with([
                    'voucherWithUsers' => function ($query) use ($voucherId) {
                        $query->select(
                            'users.id',
                            'users.dept_id',
                            'users.designation_id',
                            'users.company',
                            'users.name',
                            'users.employee_id'
                        )->where('voucher_type_permission_user.voucher_type_id', $voucherId);
                    },
                    'voucherWithUsers.department' => function ($query) {
                        $query->select('departments.id', 'departments.dept_name');
                    },
                    'voucherWithUsers.designation' => function ($query) {
                        $query->select('designations.id', 'designations.title');
                    },
                    'voucherWithUsers.getCompany' => function ($query) {
                        $query->select('company_infos.id', 'company_infos.company_name');
                    }
                ])->select(['voucher_types.id','voucher_types.voucher_type_title'])->find($voucherId);
                $view = view('back-end.archive.type.__receiver-list-modal',compact('usersWithPermission','voucherId'))->render();
                return response()->json(['data' => $view,'status' => 'success','message'=>'Request process successful']);
            }

            return response()->json([
                    'status' => 'error',
                    'message' => 'Request method not allowed!'
                ]);
        }catch (\Throwable $exception){
            return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage(),
             ]);
        }
    }
    public function deleteTypePermissionFromUser(Request $request){
      try{
        $user_id = Crypt::decryptString($request->user_id);
        $data_type_id = Crypt::decryptString($request->data_type_id);
        $request->validate([
            'user_id'=>['required','string'],
            'data_type_id'=>['required','string']
        ]);
        $deleteData = Voucher_type_permission_user::where('user_id',$user_id)->where('voucher_type_id',$data_type_id)->first();
        if($deleteData){
           $delete_history = Voucher_type_permission_user_delete_history::create([
            'old_id'=>$deleteData->id,
            'voucher_type_id'=>$deleteData->voucher_type_id,
            'user_id'=>$deleteData->user_id,
            'old_created_at'=>$deleteData->created_at,
            'old_updated_at'=>$deleteData->updated_at,
            'old_created_by'=>$deleteData->created_by,
            'old_updated_by'=>$deleteData->updated_by,
            'created_by'=>Auth::user()->id,
            'updated_by'=>null,
            'created_at'=>now()
           ]);
           if($delete_history){
                $deleteData->delete();
                return response()->json([
                    'status'=> 'success',
                    'message'=> 'Data deleted successfully!',
                ]);
           }
           return response()->json([
            'status'=> 'error',
            'message'=> 'Delete operation failed, please try again!',
        ]);
        }
        return response()->json([
            'status'=> 'error',
            'message'=> 'Data not found!',
        ]);

      }catch (\Exception $exception){
        return response()->json([
            'status'=> 'error',
            'message'=> $exception->getMessage(),
        ]);
      }

    }
    private function updateArchiveType(Request $request,$voucherTypeID)
    {
        try {
            $vtID = Crypt::decryptString($voucherTypeID);
            $request->validate([
                'data_type_title'   =>  ['required', 'string', 'max:255',Rule::unique('voucher_types','voucher_type_title')->where('company_id', $request->post('company'))->ignore($vtID,'id')],
                'data_type_code'    =>  ['sometimes','nullable', 'numeric', Rule::unique('voucher_types','code')->where('company_id', $request->post('company'))->ignore($vtID,'id')],
                'status'               =>  ['required', 'numeric'],
                'remarks'              =>  ['sometimes','nullable', 'string'],
                'company'               => ['required', 'integer', 'exists:company_infos,id'],
            ]);
            extract($request->post());
            if (!VoucherType::find($vtID))
            {
                return back()->with('error','Data not found!')->withInput();
            }
            // if (VoucherType::where('id','!=',$vtID)->where('voucher_type_title',$data_type_title)->first() || VoucherType::where('id','!=',$vtID)->where('code',$data_type_code)->first())
            // {
            //     return back()->with('error','Duplicate data found!')->withInput();
            // }
            $user = Auth::user();
            VoucherType::where('id',$vtID)->update([
                'company_id'=> $company,
                'status'    =>  $status,
                'voucher_type_title'=>  $data_type_title,
                'code'      =>  $data_type_code,
                'remarks'   =>  $remarks,
                'updated_by'=>  $user->id,
            ]);
            return back()->with('success','Data update successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function deleteArchiveType(Request $request)
    {
        $request->validate([
            'id'   =>  ['required', 'string'  ],
        ]);
        try {
            extract($request->post());
            $vtID = Crypt::decryptString($id);
            $av = VoucherType::with(['accountVoucher','voucherWithUsers'])->find($vtID);
            if($av->accountVoucher != null || count($av->voucherWithUsers)>0)
            {
                return back()->with('error','A relationship exists between other tables. Data delete not possible');
            }
            $av->delete();
            // Voucher_type_permission_user::where('voucher_type_id',$vtID)->delete();
            return back()->with('success','Data delete successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function create(Request $request)
    {
        try {
            $permission = $this->permissions()->archive_document_upload;
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }
            $voucherTypes = VoucherType::where('status',1)->get();
            $user = Auth::user();
//            $voucherInfos = Account_voucher::whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))->with(['VoucherDocument','VoucherType','createdBY','updatedBY','company','voucherDocuments'])->where('created_by',$user->id)->orWhere('updated_by',$user->id)->latest('created_at')->take(10)->get();
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end/archive/add',compact("voucherTypes","companies"))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function store(Request $request)
    {
        DB::beginTransaction();
        $uploadedFiles = []; // Track successfully uploaded files
        try {
            $request->validate([
                'company'               => ['required', 'integer', 'exists:company_infos,id'],
                'project'               => ['sometimes','nullable', 'integer', 'exists:branches,id',],
                'reference_number'    =>  ['required','string',Rule::unique('account_voucher_infos','voucher_number')->where(function ($query) use ($request){
                    return $query->where('company_id',$request->post('company'));
                })],
                'voucher_date'      =>  ['required','date'],
                'data_type'      =>  ['required','numeric','exists:voucher_types,id'],
                'remarks'           =>  ['sometimes','nullable','string'],
                'voucher_file.*'    =>  ['sometimes','nullable','max:512000'],
                'previous_files'    =>  ['sometimes','nullable','array'],
                'previous_files.*'  =>  ['sometimes','nullable','exists:voucher_documents,id'],
            ]);
            extract($request->post());
            $user = Auth::user();
            $documentIds = array();
            $company_code = company_info::find($company)->company_code;
            $v_type = VoucherType::where('id',$data_type)->first();
            $file_path = $company_code.'/'.$v_type->voucher_type_title.'/';
            $destinationPath = env('APP_ARCHIVE_DATA').'/'.$file_path;

//            $firstInsert = archive infos
            $firstInsert = DB::table('account_voucher_infos')->insertGetId([
                'company_id'        =>  $company,
                'voucher_type_id'   =>  $data_type,
                'voucher_number'    =>  $reference_number,
                'voucher_date'      =>  $voucher_date,
                'file_count'        =>  null,
                'remarks'           =>  $remarks,
                'project_id'           =>  $project,
                'created_by'        =>  $user->id,
                'created_at'        =>  now(),
            ]);
            if (!$firstInsert) {
                throw new \Exception('Failed to insert data');
            }
            if ($firstInsert && $request->hasFile('voucher_file')) {
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true); // recursive mkdir
                }
                foreach ($request->file('voucher_file') as $file) {
                    // Handle each file
                    $fileName = $reference_number."_".$v_type->voucher_type_title."_".now()->format('Ymd_His')."_".$file->getClientOriginalName();

                    $filePath = $destinationPath . $fileName;

                    if (!$file->move($destinationPath,$fileName))
                    {
                        throw new \Exception('Failed to upload file ['.$file->getClientOriginalName().']');
                    }
                    $uploadedFiles[] = $filePath; // Track the uploaded file

//            $secondInsert = documents infos
                    $secondInsert = DB::table('voucher_documents')->insertGetId([
                        'company_id'        =>  $company,
                        'voucher_info_id'   =>  $firstInsert,
                        'document'          =>  $fileName,
                        'filepath'          =>  $file_path,
                        'created_by'        =>  $user->id,
                        'created_at'        =>  now(),
                    ]);
                    array_push($documentIds,$secondInsert);
                    if (!$secondInsert) {
                        throw new \Exception('Failed to insert documents ['.$file->getClientOriginalName().'] data!');
                    }
                    $thirdInsert = ArchiveInfoLinkDocument::create([
                        'company_id'        =>  $company,
                        'voucher_info_id'   =>  $firstInsert,
                        'document_id'        =>  $secondInsert,
                    ]);
                    if (!$thirdInsert) {
                        throw new \Exception('Failed to update link between documents and info');
                    }
                }
            }
            if ($firstInsert && !empty($previous_files)) {
                $finalInsertData = $this->linkDocumentInfoArray($previous_files,$firstInsert,$documentIds,$company);
                if (!empty($finalInsertData)) {
//            $thirdInsert = archive infos and documents link
                    $thirdInsert = ArchiveInfoLinkDocument::insert($finalInsertData); // Bulk insert
                }
                if (!$thirdInsert) {
                    throw new \Exception('Failed to update link between documents and info');
                }
            }
            DB::commit();
            return back()->with('success', 'Data save successful.');

        }catch (\Throwable $exception)
        {
            DB::rollBack();

            // Delete uploaded files if transaction fails
            foreach ($uploadedFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }

            return redirect()->back()->with('error', 'An error occurred: ' . $exception->getMessage())->withInput();
        }

    }

    public function createArchiveDocumentIndividual(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $request->validate([
                'id'    => ['required','string', new AccountVoucherInfoStatusRule()],
            ]);
            try {
                $permission = $this->permissions()->add_archive_document_individual;
                extract($request->post());
                $voucherInfo = Account_voucher::where('id',Crypt::decryptString($id))->first();
                return view('back-end.archive._create_archive_document_individual_model',compact('voucherInfo'))->render();
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
        return redirect()->back()->with('error', "request method {$request->method()} not supported")->withInput();
    }
    public function storeArchiveDocumentIndividual(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'id'    => ['required','string', new AccountVoucherInfoStatusRule()],
                    'voucher_file.*'    =>  ['required','max:512000'],
                ]);
                extract($request->post());
                $user = Auth::user();
                $voucherInfo = Account_voucher::with(['VoucherType','company'])->where('id',Crypt::decryptString($id))->first();
                $company_code = $voucherInfo->company->company_code;
                $file_path = $company_code.'/'.$voucherInfo->VoucherType->voucher_type_title.'/';
                $destinationPath = env('APP_ARCHIVE_DATA').'/'.$file_path;
                $uploadedFiles = []; // Track successfully uploaded files

                foreach ($request->file('voucher_file') as $file) {
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true); // recursive mkdir
                    }
                    $fileName = $voucherInfo->voucher_number."_".$voucherInfo->VoucherType->voucher_type_title."_".now()->format('Ymd_His')."_".$file->getClientOriginalName();
                    $filePath = $destinationPath . $fileName;
                    if (!$file->move($destinationPath,$fileName))
                    {
                        throw new \Exception('Failed to upload file.['.$file->getClientOriginalName().']');
                    }
                    $uploadedFiles[] = $filePath; // Track the uploaded file
                    $insert = DB::table('voucher_documents')->insertGetId([
                        'company_id'        =>  $voucherInfo->company_id,
                        'voucher_info_id'   =>  $voucherInfo->id,
                        'document'          =>  $fileName,
                        'filepath'          =>  $file_path,
                        'created_by'        =>  $user->id,
                        'created_at'        =>  now(),
                    ]);
                    if (!$insert) {
                        throw new \Exception('Failed to execute the insert. ['.$file->getClientOriginalName().'] document');
                    }

                    $secondInsert = ArchiveInfoLinkDocument::create([
                        'company_id'        =>  $voucherInfo->company_id,
                        'voucher_info_id'   =>  $voucherInfo->id,
                        'document_id'        =>  $insert,
                    ]);
                    if (!$secondInsert) {
                        throw new \Exception('Failed to create link between ['.$voucherInfo->voucher_number.' And '.$file->getClientOriginalName().'] document');
                    }
                }
                DB::commit();
                return back()->with('success','Data upload successfully on Voucher No:'.$voucherInfo->voucher_number);
            }
            return back()->with('error', "request method {$request->method()} not supported")->withInput();
        }catch (\Throwable $exception)
        {
            DB::rollBack();

            // Delete uploaded files if transaction fails
            foreach ($uploadedFiles as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            return back()->with('error',$exception->getMessage());
        }
    }

    public function archiveList()
    {
        try {
             $permission = $this->permissions()->archive_data_list;
            // $voucherInfos = Account_voucher::with(['VoucherDocument','VoucherType','createdBY','updatedBY','voucherDocuments'])->whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))->whereIn('voucher_type_id',$this->getCompanyWiseDataTypes(null)->pluck('id')->toArray())->get();

            $voucherInfos = $this->getArchiveList($permission)->get();
            return view('back-end/archive/list',compact('voucherInfos'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function archiveListQuick(Request $request)
    {
        try {
            $permission = $this->permissions()->archive_data_list_quick;
            $dataType = null;
            $voucherInfos = null;
            if ($request->isMethod('post'))
            {
                return $this->archiveListQuickSearch($request);
            }
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            if ($request->get('c') && $request->get('t'))
            {
                $request->validate([
                    'c' =>['required', 'integer', 'exists:company_infos,id'],
                    't' =>['required', 'integer', 'exists:voucher_types,id'],
                ]);
                $companyID = $this->getCompanyModulePermissionWise($permission)->where('id',$request->get('c'))->first('id');
                $dataType = $this->archiveTypeList($permission)->where('company_id',$companyID->id)->where('id',$request->get('t'))->where('status',1)->select('id','voucher_type_title')->first();
                $voucherInfos = $this->archiveListInfo($request->get('c'),$request->get('t'));
            }
            return view('back-end/archive/quick-list',compact('companies','dataType','voucherInfos'))->render();
        }catch (\Throwable $exception)
        {
            if ($request->isMethod('post'))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => $exception->getMessage()
                ]);
            }
            return back()->with('error',$exception->getMessage());
        }
    }

    private function archiveListInfo($company_id,$type_id)
    {
        return Account_voucher::with([
            'VoucherDocument',
            'voucherDocuments',
            'VoucherType',
            'company',
            'createdBY',
            'updatedBY'
        ])
            ->select(
                'id',
                'voucher_date',
                'voucher_type_id',
                'voucher_number',
                'remarks',
                'created_at',
                'company_id',
                'voucher_type_id',
                'created_by',
                'updated_by',
                'project_id'
            )
            ->with(['VoucherDocument' => function($query) {
                $query->select('voucher_info_id', 'document', 'filepath'); // Adjust the columns in VoucherDocument
            }])
            ->with(['VoucherType' => function($query) {
                $query->select('id', 'voucher_type_title', 'code'); // Adjust the columns in VoucherType
            }])
            ->with(['company' => function($query) {
                $query->select('id', 'company_name', 'company_code'); // Adjust the columns in company
            }])
            ->with(['createdBY' => function($query) {
                $query->select('id', 'name'); // Adjust the columns in createdBY (User model)
            }])
            ->with(['updatedBY' => function($query) {
                $query->select('id', 'name'); // Adjust the columns in updatedBY (User model)
            }])
            ->where('company_id', $company_id)
            ->whereIn('voucher_type_id',$this->getCompanyWiseDataTypes($company_id)->pluck('id')->toArray())
            ->where('voucher_type_id',$type_id)->get();
    }
    private function archiveListQuickSearch(Request $request)
    {
        try {
            $permission = $this->permissions()->archive_data_list_quick;
            $validated = $request->validate([
                'company_id'    =>  ['required', 'integer', 'exists:company_infos,id'],
                'projects'      =>  ['array', 'sometimes', 'nullable'],
                'projects.*'    =>  [
                    'integer',
                    function ($attribute, $value, $fail) {
                        if ((int) $value !== 0 && !DB::table('branches')->where('id', $value)->exists()) {
                            $fail("The selected $attribute is invalid.");
                        }
                    },
                ],
                'data_types'    =>  ['array', 'sometimes', 'nullable'],
                'data_types.*'  =>  [
                    'integer', 'nullable',
                    function ($attribute, $value, $fail) {
                        if ((int) $value !== 0 && !DB::table('voucher_types')->where('id', $value)->exists()) {
                            $fail("The selected $attribute is invalid.");
                        }
                    },
                ],
                'from_date'     => ['date', 'nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
                'to_date'       => ['date', 'nullable', 'date_format:Y-m-d', 'after_or_equal:from_date'],
                'reference'     => [ 'sometimes', 'nullable', 'string' ],
            ]);
            extract($validated);
            // Assign variables safely
            $company_id = $validated['company_id'];
            $projects   = $validated['projects'] ?? [];
            $data_types = $validated['data_types'] ?? [];
            $from_date  = $validated['from_date'] ?? null;
            $to_date    = $validated['to_date'] ?? null;
            $reference  = $validated['reference'] ?? null;
            // Check if the array contains only 0
            $projects = array_filter($projects, fn($value) => $value != 0);
            $data_types = array_filter($data_types, fn($value) => $value != 0);

            $archiveInfos = Account_voucher::with([
                'VoucherDocument',
                'voucherDocuments',
                'VoucherType',
                'company',
                'createdBY',
                'updatedBY'
            ])
            ->select(
                'id',
                'voucher_date',
                'voucher_type_id',
                'voucher_number',
                'remarks',
                'created_at',
                'company_id',
                'voucher_type_id',
                'created_by',
                'updated_by',
                'project_id'
            )
            ->whereIn('project_id', $this->getUserProjectPermissions(Auth::id(),$permission)->pluck('id')->toArray())
            ->with(['VoucherDocument' => function($query) {
                $query->select('voucher_info_id', 'document', 'filepath'); // Adjust the columns in VoucherDocument
            }])
            ->with(['VoucherType' => function($query) {
                $query->select('id', 'voucher_type_title', 'code'); // Adjust the columns in VoucherType
            }])
            ->with(['company' => function($query) {
                $query->select('id', 'company_name', 'company_code'); // Adjust the columns in company
            }])
            ->with(['createdBY' => function($query) {
                $query->select('id', 'name'); // Adjust the columns in createdBY (User model)
            }])
            ->with(['updatedBY' => function($query) {
                $query->select('id', 'name'); // Adjust the columns in updatedBY (User model)
            }])
            ->where('company_id', $company_id)->whereIn('voucher_type_id',$this->getCompanyWiseDataTypes($company_id)->pluck('id')->toArray());
            if (!empty($projects)) {
                $archiveInfos->whereIn('project_id', $projects);
            }

            if (!empty($data_types)) {
                $archiveInfos->whereIn('voucher_type_id', $data_types);
            }

            // Apply date filtering only if both dates exist
            if ($from_date && $to_date) {
                $archiveInfos->whereBetween('voucher_date', [$from_date, $to_date]);
            }

            if (!empty($reference)) {
                $archiveInfos->where('voucher_number','LIKE', "%$reference%");
            }

            $archiveInfos = $archiveInfos->get();
            $voucherInfos = $archiveInfos;
            $view = view('back-end.archive._archive_quick_list', compact('voucherInfos'))->render();
            return response()->json([
                'status' => 'success',
                'data' => [$view],
                'message' => 'Request processed successfully.',
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'    => 'error',
                'message'   => $exception->getMessage(),
                'exception' => $exception
            ]);
        }
    }
    // {
    //     if ($this->user->isSystemSuperAdmin() || $this->user->companyWiseRoleName() == 'superadmin')
    //     {
    //         if ($company_id == null)
    //         {
    //             $userWiseVoucherTypePermissionId = VoucherType::all()->pluck('id')->toArray();
    //         }
    //         else {
    //             $userWiseVoucherTypePermissionId = VoucherType::where('company_id',$company_id)->get()->pluck('id')->toArray();
    //         }
    //     }
    //     else
    //     {
    //         if ($company_id == null)
    //         {
    //             $userWiseVoucherTypePermissionId = Voucher_type_permission_user::where('user_id',$this->user->id)->get()->pluck('voucher_type_id')->toArray();
    //         }
    //         else {
    //             $userWiseVoucherTypePermissionId = Voucher_type_permission_user::where('company_id',$company_id)->where('user_id',$this->user->id)->get()->pluck('voucher_type_id')->toArray();
    //         }
    //     }
    //     if ($company_id == null)
    //     {
    //         return VoucherType::where('status',1)->whereIn('id',$userWiseVoucherTypePermissionId)->get();
    //     }
    //     else{
    //         return VoucherType::where('company_id',$company_id)->where('status',1)->whereIn('id',$userWiseVoucherTypePermissionId)->get();
    //     }

    // }
    public function companyWiseProjectsAndDataType(Request $request)
    {
        try {
            $permission = $this->permissions()->data_archive;
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $projects = branch::where('company_id',$company_id)->whereIn('id',$this->getUserProjectPermissions(Auth::user()->id,$permission)->pluck('id')->toArray())->where('status',1)->get();
                $userWiseVoucherTypePermissionId = null;
                $types = $this->getCompanyWiseDataTypes($company_id);
                return response()->json([
                    'status' => 'success',
                    'data' => ['projects' => $projects,'types' => $types],
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

    public function archiveDocumentView($vID)
    {
        try {
            $id = Crypt::decryptString($vID);
            $document = VoucherDocument::with(['accountVoucherInfo','accountVoucherInfo.VoucherType'])->find($id);
            return view('back-end/archive/single-view',compact('document'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function archiveDocumentEdit(Request $request, $vID)
    {
        try {
            $permission = $this->permissions()->edit_archive_data_type;
            if ($request->isMethod('put'))
            {
                return $this->updateVoucherDocument($request,$vID);
            }
            $vID = Crypt::decryptString($vID);
            $voucherTypes = VoucherType::where('status',1)->get();
            $voucherInfo = Account_voucher::with(['VoucherDocument','VoucherType','createdBY','updatedBY','voucherDocuments','company.projects'])->find($vID);
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end/archive/edit',compact('voucherTypes','voucherInfo','companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function linkedUploadedDocument(Request $request)
    {
        try {
            $permission = $this->permissions()->edit_archive_data_type;
            if ($request->isMethod('put'))
            {
                $validated  = $request->validate([
                    'company_id_link' => ['required','string', 'exists:company_infos,id'],//hidden input
                    'update_archive_info_id' => ['required','string', 'exists:account_voucher_infos,id'], //hidden input
                    'previous_files'  => ['required','array'],
                    'previous_files.*'  => ['required','string','exists:voucher_documents,id'],
                ]);
                extract($validated);
                $thisArchiveDocumentIDs = VoucherDocument::where('voucher_info_id',$update_archive_info_id)->get(['id'])->pluck('id')->toArray();
                if (!empty($previous_files)) {
                    $finalInsertData = $this->linkDocumentInfoArray($previous_files,$update_archive_info_id,$thisArchiveDocumentIDs,$company_id_link);
                    if (!empty($finalInsertData)) {
//            $thirdInsert = archive infos and documents link
                        $thirdInsert = ArchiveInfoLinkDocument::insert($finalInsertData); // Bulk insert
                        if (!$thirdInsert) {
                            // Rollback the transaction if the second insert for any item failed
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Failed to execute the second insert.');
                        }
                    }
                    else{
                        return back()->with('success', 'Data already updated.');
                    }
                }
                return back()->with('success', 'Data uploaded successfully');
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    private function linkDocumentInfoArray($previous_files,$documentInfoId,$documentIds,$company_id): array
    {
        $previous_documents = VoucherDocument::whereIn('id', $previous_files)->get();
        $existingLinks = ArchiveInfoLinkDocument::where('voucher_info_id', $documentInfoId)->whereIn('document_id', $previous_documents->pluck('id'))->whereIn('company_id', $previous_documents->pluck('company_id'))->get()->pluck('document_id')->toArray();

        $childLinkData = $previous_documents->reject(function ($previous_document) use ($existingLinks) {
            return in_array($previous_document->id, $existingLinks);
        })->map(function ($previous_document) use ($documentInfoId) {
            return [
                'company_id'      => $previous_document->company_id,
                'voucher_info_id' => $documentInfoId,
                'document_id'     => $previous_document->id,
                'created_at'      => now(),
            ];
        })->values()->toArray();

        $previous_document_single = $previous_documents->first();
        $existingLinks2 = ArchiveInfoLinkDocument::where('voucher_info_id', $previous_document_single->voucher_info_id)->whereIn('document_id', $documentIds)->where('company_id', $company_id)->get()->pluck('document_id')->toArray();

//        $parentLinkData = collect($documentIds)->reject(function ($documentId) use ($existingLinks2){
//            return in_array($documentId, $existingLinks2);
//        })->map(function ($documentId) use ($previous_document_single,$company_id) {
//            return [
//                'company_id'      => $company_id,
//                'voucher_info_id' => $previous_document_single->voucher_info_id,
//                'document_id'        => $documentId,
//                'created_at'        => now(),
//            ];
//        })->toArray();

        $parentLinkData = collect($documentIds)
            ->reject(fn($documentId) => in_array($documentId, $existingLinks2))
            ->map(fn($documentId) => [
                'company_id'      => $company_id,
                'voucher_info_id' => $previous_document_single->voucher_info_id,
                'document_id'     => $documentId,
                'created_at'      => now(),
            ])->values()->toArray();
        return array_merge($childLinkData, $parentLinkData);
    }
    private function updateVoucherDocument(Request $request, $vID)
    {
        try {
            $permission = $this->permissions()->edit_archive_data_type;
            $vID = Crypt::decryptString($vID);
            $request->validate([
                'company'               => ['required', 'integer', 'exists:company_infos,id'],
                'project'               =>  ['sometimes','nullable'],
//                'voucher_number'    =>  ['required','string','unique:account_voucher_infos,voucher_number,'.$vID, Rule::unique('account_voucher_infos')->ignore($vID)],
                'reference_number'    =>  ['required','string',Rule::unique('account_voucher_infos','voucher_number')->where(function ($query) use ($request){
                    return $query->where('company_id',$request->post('company'));
                })->ignore($vID)],
                'voucher_date'      =>  ['required','date'],
                'data_type'      =>  ['required','numeric','exists:voucher_types,id'],
                'remarks'           =>  ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            $voucherInfo = Account_voucher::find($vID);
            Account_voucher::where('id',$vID)->update([
                'company_id'        =>  $company,
                'voucher_type_id'   =>  $data_type,
                'voucher_number'    =>  $reference_number,
                'voucher_date'      =>  $voucher_date,
                'remarks'           =>  $remarks,
                'project_id'           =>  $project
            ]);
            if ($voucherInfo->company_id != $company)
            {
                VoucherDocument::where('voucher_info_id',$vID)->update([
                    'company_id'        =>  $company,
                ]);
            }
            return back()->with('success','Data updated successfully on Voucher No:'.$voucherInfo->voucher_number);
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function deleteArchiveDocumentIndividual(Request $request)
    {
        try {
            if ($request->isMethod('delete'))
            {
                $request->validate([
                    'id'  =>    ['required','string']
                ]);
                extract($request->post());
                $user = Auth::user();
                $id = Crypt::decryptString($id);
                $v_d = VoucherDocument::where('id',$id)->first();
                if ($v_d)
                {
                    $this->deleteArchiveDocumentIndividualWithHistory($v_d,Auth::id());
//                    VoucherDocumentIndividualDeletedHistory::create([
//                        'company_id'        =>  $v_d->company_id,
//                        'voucher_info_id'   =>  $v_d->voucher_info_id,
//                        'document'          =>  $v_d->document,
//                        'filepath'          =>  $v_d->filepath,
//                        'created_by'        =>  $v_d->created_by,
//                        'updated_by'        =>  $v_d->updated_by,
//                        'deleted_by'        =>  $user->id,
//                        'created_at'        =>  now(),
//                    ]);
//                    VoucherDocument::where('id',$id)->delete();
                    return back()->with('success','Data delete successfully');
                }
                return back()->with('error','Data not found on database!');
            }
            return back()->with('error','Requested data not valid!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    private function deleteArchiveDocumentIndividualWithHistory($v_d,$deleted_by)
    {
        try {
            VoucherDocumentIndividualDeletedHistory::create([
                'company_id'        =>  $v_d->company_id,
                'voucher_info_id'   =>  $v_d->voucher_info_id,
                'document'          =>  $v_d->document,
                'filepath'          =>  $v_d->filepath,
                'created_by'        =>  $v_d->created_by,
                'updated_by'        =>  $v_d->updated_by,
                'deleted_by'        =>  $deleted_by,
                'created_at'        =>  now(),
            ]);
            $old_link_data = ArchiveInfoLinkDocument::where('voucher_info_id',$v_d->voucher_info_id)->where('document_id',$v_d->id)->first();
            ArchiveLinkDocumentDeleteHistory::create([
                'old_id' => $old_link_data->id,
                'voucher_info_id' =>  $old_link_data->voucher_info_id,
                'company_id' =>   $old_link_data->company_id,
                'document_id' =>   $old_link_data->voucher_info_id,
                'old_created_at'  =>  $old_link_data->created_at,
                'old_updated_at'   =>  $old_link_data->updated_at,
            ]);
            VoucherDocument::where('id',$v_d->id)->delete();
            $old_link_data->delete();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function voucherMultipleSubmit(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $submitButtonName = $request->input('submit_selected');
            $selectedCheckboxes = $request->input('selected', []);
            dd($selectedCheckboxes);
        }
    }

    public function delete(Request $request)
    {
        try {
            $permission = $this->permissions()->archive_data_delete;
                $request->validate([
                    'id'       => ['required_without:selected', 'string'],  // 'id' is required if 'selected' is missing
                    'selected' => ['required_without:id', 'array'],        // 'selected' is required if 'id' is missing
                    'selected.*' => ['string'],
                ]);
                $user = Auth::user();
                if($request->multipleDlt){
                    $ids = array_map(function ($id) {
                        return $id;
                    }, $request->selected);

                    $deletion_check = 0;
                    foreach ($ids as $id){
                        $v = Account_voucher::with(['VoucherDocument'])->where('id',$id)->first();
                        if(isset($v) && $this->deleteVoucherInfoWithHistory($v))
                        {
                            $deletion_check++;
                        }
                    }
                    if ($deletion_check){
                    return response()->json([
                                'status'=>'success',
                                'message'=> $deletion_check.' Data delete successfully!'
                        ]);
                    }else{
                        return response()->json([
                        'status'=>'error',
                        'message'=> 'Data not found on database!'
                    ]);
                    }
                }else{
                    $id = Crypt::decryptString($request->id);
                    $v = Account_voucher::with(['VoucherDocument'])->where('id',$id)->first();
                    if(isset($v) && $this->deleteVoucherInfoWithHistory($v))
                    {
                        return back()->with('success','Data delete successfully!');
                    }else{
                        return back()->with('error','Data not found on database!');
                    }
                }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    private function deleteVoucherInfoWithHistory($data)
    {
        try {
            if (!$data)
            {
                return false;
            }
            if (isset($data->VoucherDocument) && count($data->VoucherDocument)>0)
            {
                foreach ($data->VoucherDocument as $v_d)
                {
                    $this->deleteArchiveDocumentIndividualWithHistory($v_d,Auth::id());
                }
            }
            VoucherDocumentDeleteHistory::create([
                'old_id' => $data->id,
                'company_id'  => $data->company_id,
                'voucher_type_id'  => $data->voucher_type_id,
                'voucher_date'   => $data->voucher_date,
                'voucher_number' => $data->voucher_number,
                'file_count'  => $data->file_count,
                'remarks'   => $data->remarks,
                'old_created_by'    => $data->created_by,
                'old_updated_by'     => $data->updated_by,
                'old_created_at'     => $data->created_at,
                'old_updated_at'      => $data->updated_at,
                'created_by'   => Auth::id(),
            ]);
            $data->delete();
            return true;
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function searchPreviousDocumentRef(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'value' => ['required','string'],
                    'company' =>  ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $data = Account_voucher::where('company_id',$company)->where(function ($query) use ($value) {
                    $query->where('voucher_number', 'like', "%{$value}%")
                        ->orWhere('remarks', 'like', "%{$value}%");
                })->select(['id', 'voucher_number'])
                    ->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                    'message' => 'Requested data found successfully!'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=>'Requested method are not allowed!'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message'=> $exception->getMessage()
            ]);
        }
    }
    public function searchPreviousDocument(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'ids' => ['required','array'],
                    'ids.*' => ['string',function ($attribute, $value, $fail) {
                        if ($value != 0) {
                            // Apply the exists rule only when pid is not 0
                            $exists = DB::table('account_voucher_infos')
                                ->where('id', $value)
                                ->exists();

                            if (!$exists) {
                                $fail('The selected pid is invalid.');
                            }
                        }
                    }],
                ]);
                extract($request->post());
                $data = VoucherDocument::whereIn('voucher_info_id',$ids)->select(['id', 'document'])
                    ->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                    'message' => 'Requested data found successfully!'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=>'Requested method are not allowed!'
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message'=> $exception->getMessage()
            ]);
        }
    }
    public function searchCompanyDepartmentUsers(Request $request){
        try {
            $permission = $this->permissions()->add_archive_data_type;
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'ids'=> ['required','array'],
                    'ids.*'=> ['string','numeric','exists:departments,id'],
                    'company_id'=> ['string','numeric','exists:company_infos,id']
                    ]);
                extract($request->post());

                $users = $this->getUser($permission)->where('company',$company_id)->whereIn('dept_id',$ids)->get(['id','name']);

                return response()->json([
                    'status'=> 'success',
                    'data'=>$users,
                    'message' => 'Request process successfully!',
                ]);
            }
            return response()->json([
                'status'=> 'error',
                'message'=> 'Requested method are not allowed!'
                ]);

        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=> 'error',
                'message'=> $exception->getMessage()
                ]);
        }


        // $departments = department::with(['get_users' => function($query) {
        //     $query->whereColumn('company_id', 'company'); // Compare company_id with company directly
        // }])
        // ->get();
    }

    public function setting()
    {
        try {
            $permission = $this->permissions()->archive_setting;
            $companies = $this->getCompanyModulePermissionWise($permission)->get(['id','company_name','company_code']);
            return view('back-end.archive.setting',compact('companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
}
