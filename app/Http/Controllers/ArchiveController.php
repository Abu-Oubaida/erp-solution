<?php

namespace App\Http\Controllers;

use App\Models\Account_voucher;
use App\Models\ArchiveInfoLinkDocument;
use App\Models\ArchiveLinkDocumentDeleteHistory;
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
    private $accounts_document_path = "file-manager/Account Document/";

    public function createArchiveType(Request $request)
    {
        $permission = $this->permissions()->add_archive_data_type;
        try {
            if ($request->isMethod('post'))
            {
                return $this->storeArchiveType($request);
            }
            $voucherTypes = $this->archiveTypeList();
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
//            dd($voucherTypes);
            return view('back-end/archive/type/add',compact('voucherTypes','companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    private function storeArchiveType(Request $request):RedirectResponse
    {
        $request->validate([
            'data_type_title'   =>  ['required', 'string', 'max:255'],
            'data_type_code'    =>  ['sometimes','nullable', 'numeric'],
            'status'               =>  ['required', 'numeric'],
            'remarks'              =>  ['sometimes','nullable', 'string'],
            'company'               => ['required', 'integer', 'exists:company_infos,id'],
        ]);
        extract($request->post());
        try {
            if (VoucherType::where('voucher_type_title',$data_type_title)->orWhere('code',$data_type_code)->first())
            {
                return back()->with('error','Duplicate data found!')->withInput();
            }
            $user = Auth::user();
            VoucherType::create([
                'company_id'=> $company,
                'status'    =>  $status,
                'voucher_type_title'=>  $data_type_title,
                'code'      =>  $data_type_code,
                'remarks'   =>  $remarks,
                'created_by'=>  $user->id,
                'updated_by'=>  $user->id,
            ]);
            return back()->with('success','Data insert successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
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
            $voucherTypes = $this->archiveTypeList();
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end/archive/type/edit',compact('voucherType','voucherTypes','companies'))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function archiveTypeList()
    {
        return VoucherType::with(['createdBY','updatedBY'])->get();
    }
    private function updateArchiveType(Request $request,$voucherTypeID)
    {
        $request->validate([
            'data_type_title'   =>  ['required', 'string', 'max:255'],
            'data_type_code'    =>  ['sometimes','nullable', 'numeric'],
            'status'               =>  ['required', 'numeric'],
            'remarks'              =>  ['sometimes','nullable', 'string'],
            'company'               => ['required', 'integer', 'exists:company_infos,id'],
        ]);
        extract($request->post());
        try {
            $vtID = Crypt::decryptString($voucherTypeID);
            if (!VoucherType::find($vtID))
            {
                return back()->with('error','Data not found!')->withInput();
            }
            if (VoucherType::where('id','!=',$vtID)->where('voucher_type_title',$data_type_title)->first() || VoucherType::where('id','!=',$vtID)->where('code',$data_type_code)->first())
            {
                return back()->with('error','Duplicate data found!')->withInput();
            }
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
            $av = VoucherType::with(['accountVoucher'])->find($vtID);
            if($av->accountVoucher != null)
            {
                return back()->with('error','A relationship exists between other tables. Data delete not possible');
            }
            VoucherType::where('id',$vtID)->delete();

            return redirect(route('add.archive.type'))->with('success','Data delete successfully');
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
            $voucherInfos = Account_voucher::whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))->with(['VoucherDocument','VoucherType','createdBY','updatedBY','company','voucherDocuments'])->where('created_by',$user->id)->orWhere('updated_by',$user->id)->latest('created_at')->take(10)->get();
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end/archive/add',compact("voucherTypes","voucherInfos","companies"))->render();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'company'               => ['required', 'integer', 'exists:company_infos,id'],
                'project'               => ['required', 'integer', 'exists:branches,id'],
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
            $v_type = VoucherType::where('id',$data_type)->first();
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
                $documentIds = array();
                $uploadedFiles = []; // Track successfully uploaded files
                foreach ($request->file('voucher_file') as $file) {
                    // Handle each file
                    $fileName = $reference_number."_".$v_type->voucher_type_title."_".now()->format('Ymd_His')."_".$file->getClientOriginalName();

                    $filePath = $this->accounts_document_path . '/' . $fileName;

                    if (!$file->move($this->accounts_document_path,$fileName))
                    {
                        throw new \Exception('Failed to upload file ['.$file->getClientOriginalName().']');
                    }
                    $uploadedFiles[] = $filePath; // Track the uploaded file

//            $secondInsert = documents infos
                    $secondInsert = DB::table('voucher_documents')->insertGetId([
                        'company_id'        =>  $company,
                        'voucher_info_id'   =>  $firstInsert,
                        'document'          =>  $fileName,
                        'filepath'          =>  $this->accounts_document_path,
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
                $voucherInfo = Account_voucher::with(['VoucherType'])->where('id',Crypt::decryptString($id))->first();
                $uploadedFiles = []; // Track successfully uploaded files

                foreach ($request->file('voucher_file') as $file) {
                    $fileName = $voucherInfo->voucher_number."_".$voucherInfo->VoucherType->voucher_type_title."_".now()->format('Ymd_His')."_".$file->getClientOriginalName();
                    $filePath = $this->accounts_document_path . '/' . $fileName;
                    if (!$file->move($this->accounts_document_path,$fileName))
                    {
                        throw new \Exception('Failed to upload file.['.$file->getClientOriginalName().']');
                    }
                    $uploadedFiles[] = $filePath; // Track the uploaded file
                    $insert = DB::table('voucher_documents')->insertGetId([
                        'company_id'        =>  $voucherInfo->company_id,
                        'voucher_info_id'   =>  $voucherInfo->id,
                        'document'          =>  $fileName,
                        'filepath'          =>  $this->accounts_document_path,
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
//        try {
            $permission = $this->permissions()->archive_data_list;
            $voucherInfos = Account_voucher::whereIn('company_id',$this->getCompanyModulePermissionWiseArray($permission))->with(['VoucherDocument','VoucherType','createdBY','updatedBY','voucherDocuments'])->get();
            return view('back-end/archive/list',compact('voucherInfos'))->render();
//        }catch (\Throwable $exception)
//        {
//            return back()->with('error',$exception->getMessage());
//        }
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
            if ($request->isMethod('delete'))
            {
                $request->validate([
                    'id'  =>    ['required','string']
                ]);
                extract($request->post());
                $user = Auth::user();
                $id = Crypt::decryptString($id);
                $v = Account_voucher::with(['VoucherDocument'])->where('id',$id)->first();
                if(isset($v) && $this->deleteVoucherInfoWithHistory($v))
                {
                    return back()->with('success','Data delete successfully!');
                }
                return back()->with('error','Data not found on database!');
            }
            return back()->with('error','Requested data not valid!');
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
}
