<?php

namespace App\Http\Controllers;

use App\Models\fixed_asset_specifications;
use App\Models\Fixed_asset_transfer;
use App\Models\Fixed_asset_transfer_delete_history;
use App\Models\Fixed_asset_transfer_document;
use App\Models\Fixed_asset_transfer_document_delete_history;
use App\Models\Fixed_asset_transfer_edit_history;
use App\Models\Fixed_asset_transfer_with_spec;
use App\Models\Fixed_asset_transfer_with_spec_delete_history;
use App\Rules\GpUniqueRefCheck;
use App\Traits\ParentTraitCompanyWise;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FixedAssetTransferController extends Controller
{
    use ParentTraitCompanyWise;
    private $document_path = "documents/fixed-asset/";
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    public function index(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_transfer_entry;
            $from_projects = null;
            $to_projects = null;
            if ($request->get('from_c'))
            {
                $from_projects = $this->getBranch($permission)->where('company_id', $request->get('from_c'))->get();
            }
            if ($request->get('to_c'))
            {
                $to_projects = $this->getBranch($permission)->where('company_id', $request->get('to_c'))->get();
            }
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.asset.transfer.index',compact('companies','from_projects','to_projects'));
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            if ($request->isMethod('POST')) {
                $permission_entry = $this->permissions()->fixed_asset_transfer_entry;
                $data = $request->validate([
                    'from_company_id' => ['sometimes','string', 'nullable', 'exists:company_infos,id'],
                    'to_company_id' => ['sometimes','string', 'nullable', 'exists:company_infos,id'],
                    'from_branch_id' => ['sometimes','string', 'nullable', 'exists:branches,id'],
                    'to_branch_id' => ['sometimes','string', 'nullable', 'exists:branches,id'],
//                    'gp_reference' => ['sometimes','string','nullable', function ($attribute, $value, $fail) use ($request) {
//                        $existingRecord = Fixed_asset_transfer::where('reference', $value)->first();
//                        if ($existingRecord) {
//                            if ($existingRecord->status != 0) {
//                                // Fail if reference exists with a non-zero status
//                                $fail("The reference is already in use.");
//                            } elseif (
//                                // Allow only if all required fields match when status is 0
//                                $existingRecord->from_company_id != $request->post('from_company_id') ||
//                                $existingRecord->to_company_id != $request->post('to_company_id') ||
//                                $existingRecord->from_project_id != $request->post('from_branch_id') ||
//                                $existingRecord->to_project_id != $request->post('to_branch_id')
//                            ) {
//                                $fail("The reference exists but does not match the required company and project details.");
//                            }
//                        }
//                    },],
                    'gp_reference' => ['sometimes','string','nullable',],
                    'gp_date' => ['sometimes','nullable','date', 'date_format:Y-m-d'],
                ]);
                extract($data);
                if (!empty($from_company_id) && !empty($from_branch_id) && !empty($to_company_id) && !empty($to_branch_id) && !empty($gp_reference))//Input Part
                {
                    $from_company = $this->getCompanyModulePermissionWise($permission_entry)->where('id',$from_company_id)->first();
//                    if (empty($from_company))
//                    {
//                        return response()->json([
//                            'status' => 'error',
//                            'data' => ['view'=>''],
//                            'message' => "you don't have permission to access from company"
//                        ]);
//                    }
                    $to_company = $this->getCompanyModulePermissionWise($permission_entry)->where('id',$to_company_id)->first();
                    $from_project = $this->getBranch($permission_entry)->select(['id','branch_name'])->where('company_id',$from_company_id)->where('id',$from_branch_id)->first();
                    $to_project = $this->getBranch($permission_entry)->select(['id','branch_name'])->where('company_id',$to_company_id)->where('id',$to_branch_id)->first();
                    $fixed_asset_ids  = $this->getFixedAssetStockMaterials($permission_entry,[$from_project->id],$from_company->id);//error
                    $fixed_assets = $this->getFixedAssets($permission_entry)->whereIn('id',$fixed_asset_ids)->get();

                    $transferData = $this->getFixedAssetGpAll($permission_entry)->where('reference',$gp_reference)->first();
                    if (!empty($transferData))
                    {
                        if ($transferData->from_company_id != $from_company_id || $transferData->to_company_id != $to_company_id || $transferData->from_project_id != $from_branch_id || $transferData->to_project_id != $to_branch_id)
                        {
                            return response()->json([
                                'status' => 'error',
                                'data' => ['view'=>''],
                                'message' => 'The reference number already exists. But selected other information is incorrect.'
                            ]);
                        }
                    }
                    if ($transferData && $transferData->status >= 1 ) {
                        if ($this->user->hasPermission('fixed_asset_transfer_list')) {
                            $transferDatas = $this->getFixedAssetGpAll($permission_entry)->where('reference',$gp_reference)->get();
                            $view = view('back-end/asset/transfer/_fixed_asset_transfer_list_active',compact('transferDatas'))->render();
                            return response()->json([
                                'status' => 'success',
                                'data' => ['view'=>$view],
                                'message' => 'Data process successfully.'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'You do not have permission to access this page.'
                        ]);
                    }
                    else
                    {
                        if ($transferData == null && is_null($gp_date))
                        {
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Gp date is required.'
                            ]);
                        }
                        if ($this->user->hasPermission('fixed_asset_transfer_entry')) {
                            if (is_null($gp_date))
                            {
                                $gp_date = $transferData->date;
                            }
                            $view = view('back-end/asset/transfer/_transfer_body_part_1',compact('fixed_assets','from_company','to_company','from_project','to_project','gp_date','gp_reference','transferData'))->render();
                            return response()->json([
                                'status' => 'success',
                                'data' => ['view'=>$view,'data'=>$transferData],
                                'message' => 'Data process successfully.'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'You do not have permission to access this page.'
                        ]);
                    }
                }
                else{//Report part
                    if ($this->user->hasPermission('fixed_asset_transfer_list'))
                    {
                        $Data = $this->getFixedAssetGpAll('fixed_asset_transfer_list');
                        if (!empty($from_company_id) && empty($from_branch_id) && empty($to_company_id) && empty($to_branch_id) && empty($gp_reference)) {
                            $transferDatas = $Data->where('from_company_id',$from_company_id)->get();
                        }
                        elseif (!empty($from_company_id) && !empty($from_branch_id) && empty($to_company_id) && empty($to_branch_id) && empty($gp_reference))
                        {
                            $transferDatas = $Data->where('from_company_id',$from_company_id)->where('from_project_id',$from_branch_id)->get();
                        }
                        elseif (!empty($from_company_id) && !empty($from_branch_id) && !empty($to_company_id) && empty($to_branch_id) && empty($gp_reference))
                        {
                            $transferDatas= $Data->where('from_company_id',$from_company_id)->where('from_project_id',$from_branch_id)->where('to_company_id',$to_company_id)->get();
                        }
                        elseif (!empty($from_company_id) && !empty($from_branch_id) && !empty($to_company_id) && !empty($to_branch_id) && empty($gp_reference))
                        {
                            $transferDatas= $Data->where('from_company_id',$from_company_id)->where('from_project_id',$from_branch_id)->where('to_company_id',$to_company_id)->where('to_project_id',$to_branch_id)->get();
                        }
                        elseif ((empty($from_company_id) || empty($from_branch_id)) && !empty($to_company_id) && empty($to_branch_id) && empty($gp_reference))
                        {
                            $transferDatas= $Data->where('to_company_id',$to_company_id)->get();
                        }
                        elseif ((empty($from_company_id) || empty($from_branch_id)) && !empty($to_company_id) && !empty($to_branch_id) && empty($gp_reference))
                        {
                            $transferDatas= $Data->where('to_company_id',$to_company_id)->where('to_project_id',$to_branch_id)->get();
                        }
                        else{
                            $transferDatas = null;
                        }
                        if ($transferDatas)
                        {
                            $view = view('back-end/asset/transfer/_fixed_asset_transfer_list_active',compact('transferDatas'))->render();
                            return response()->json([
                                'status' => 'success',
                                'data' => ['view'=>$view],
                                'message' => 'Data process successfully.'
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Data not found.'
                        ]);
                    }
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You do not have permission to access this page.'
                    ]);
                }
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request method not allowed.'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function addToListFixedAssetGp(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_transfer_entry;
            if ($request->isMethod('POST')) {
                $data = $request->validate([
                    'from_company_id' => ['string', 'required', 'exists:company_infos,id'],
                    'to_company_id' => ['string', 'required', 'exists:company_infos,id'],
                    'from_project_id' => ['string', 'required', 'exists:branches,id'],
                    'to_project_id' => ['string', 'required', 'exists:branches,id'],
                    'gp_date' => ['date', 'date_format:d-M-y'],
                    'reference' => ['required','string'],
                    'materials_id'=>    ['required','string','exists:fixed_assets,id',],
                    'specification'=>   ['required','string','exists:fixed_asset_specifications,id'],
                    'rate'  =>  ['required','numeric'],
                    'stock' =>  ['required','numeric','gte:qty'],
                    'qty'   =>  ['required','numeric','lte:stock'],
                    'purpose'=> ['sometimes','nullable','string'],
                    'remarks'=> ['sometimes','nullable','string'],
                ]);
                extract($data);
                $stock = $this->getFixedAssetSpecificationWiseStockBalance($permission,$specification,[$materials_id],[$from_project_id],$from_company_id);
                if ($stock<$qty)
                {
                    return response()->json([
                        'status'=>'error',
                        'message'=>'Requisition quantity cannot be greater than available stock balance.'
                    ]);
                }
                if ($to_company_id && $materials_id)
                {
                    $item = $this->getFixedAssets($permission)->where('id',$materials_id)->first();
                    if ($item && !($this->getFixedAssets($permission)->where('company_id',$to_company_id)->where('materials_name','like',"%{$item->materials_name}%")->exists()))
                    {
                        return response()->json([
                            'status'=>'error',
                            'message'=>'The selected materials are not available in the specified destination company.'
                        ]);
                    }
                }
                //Work running here
                $previousData = $this->getFixedAssetGpAll($permission)->where('to_company_id',$to_company_id)->where('from_company_id',$from_company_id)->where('from_project_id',$from_project_id)->where('to_project_id',$to_project_id)->where('reference',$reference)->first();
                if ($previousData == null)
                {
                    $ref_duplicate = $this->getFixedAssetGpAll($permission)->where('reference',$reference)->first();
                    if ($ref_duplicate !== null)
                    {
                        return response()->json([
                            'status' => 'warning',
                            'message' => 'This reference already exists.'
                        ]);
                    }
                    //At 1st need to entry in Fixed_asset_transfer
                    $previousData = $this->getFixedAssetGpAll($permission)->create([
                        'status' => 0,// 0=running
                        'date' => date('Y-m-d H:i:s' , strtotime($gp_date)),
                        'reference' => $reference,
                        'from_company_id' => $from_company_id,
                        'from_project_id' => $from_project_id,
                        'to_company_id' => $to_company_id,
                        'to_project_id' => $to_project_id,
                        'created_by' => $this->user->id,
                        'updated_by' => null,
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
                if ($previousData)
                {
                    $specificationData = $this->getFixedAssetGpSpecificationAll($permission)->where(function ($query) use ($previousData, $reference){
                        $query->where('transfer_id',$previousData->id)->orWhere('reference',$reference);
                    })->where('asset_id',$materials_id)->where('spec_id',$specification)->first();
                    if ($specificationData)
                    {
                        return response()->json([
                            'status' => 'warning',
                            'message' => 'This item already exists.'
                        ]);
                    }
                    $specificationDataNew = $this->getFixedAssetGpSpecificationAll($permission)->create([
                        'date' => date('Y-m-d H:i:s' , strtotime($gp_date)),
                        'transfer_id' => $previousData->id,
                        'reference' => $reference,
                        'asset_id' => $materials_id,
                        'spec_id' => $specification,
                        'rate' => $rate,
                        'qty' => $qty,
                        'purpose' => $purpose,
                        'remarks' => $remarks,
                        'created_by' => $this->user->id,
                        'updated_by' => null,
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                    if ($specificationDataNew)
                    {
                        $transferData = $this->getFixedAssetGpAll($permission)->where(function ($query) use ($previousData, $reference){
                            $query->where('id',$previousData->id)->orWhere('reference',$reference);
                        })->first();
                        $view = view('back-end/asset/transfer/__list_table_only',compact('transferData'))->render();
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Data process successfully.',
                            'data' => ['view' => $view,'data'=>$transferData],
                        ]);
                    }
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Something went wrong.',
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later.'
                ]);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request method not allowed.'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function deleteFixedAssetTransferSpec(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_transfer_entry;
            if ($request->isMethod('delete'))
            {
                $data = $request->validate([
                    'id' => ['required','numeric','exists:fixed_asset_transfer_with_specs,id'],
                ]);
                extract($data);
                if ($request->isMethod('delete'))
                {
                    extract($request->post());
                    $update_data = Fixed_asset_transfer_with_spec::where('id', $id)->first();
                    $reference = $update_data->reference;
                    if ($update_data) {
                        $this->delete_fixed_asset_transfer_balance_spec($update_data->id);

                        // Refresh the model instance to get the updated data
                        $update_data = $update_data->fresh();
                    }
                    $transferData = $this->getFixedAssetGpAll($permission)->where('reference',$reference)->orderBy('created_at','DESC')->first();
                    $view = view('back-end/asset/transfer/__list_table_only',compact('transferData'))->render();
                    return \response()->json([
                        'status'=>'success',
                        'data'=>['view' => $view,'data'=>$transferData],
                        'message'=>'Data deleted successfully.'
                    ],200);
                }
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Request method not allowed.'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function editFixedAssetTransferSpec(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $permission = $this->permissions()->fixed_asset_transfer_entry;
                $inputData = $request->validate([
                    'id'=>['required',Rule::exists('fixed_asset_transfer_with_specs','id')],
                ]);
                extract($inputData);
                $data = $this->getFixedAssetGpSpecificationAll($permission)->whereId($id)->first();
                if ($data == null)
                {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data not found.'
                    ]);
                }
                $stock = $this->getFixedAssetSpecificationWiseStockBalance($permission,$data->spec_id,[$data->asset_id],[$data->fixed_asset_transfer->from_project_id],$data->fixed_asset_transfer->from_company_id);
                $view = view('back-end/asset/transfer/__edit_fixed_asset_gp_spec',compact('data','stock'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>['view' => $view,'data'=>$data],
                    'message'=>'Request processed successfully.'
                ]);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ]);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ]);
        }
    }
    public function updateFixedAssetTransferSpec(Request $request)
    {
        try {
            if ($request->isMethod('put'))
            {
                $permission = $this->permissions()->fixed_asset_transfer_entry;
                $inputData = $request->validate([
                    'gp_date'    => ['required','date'],
                    'id' => ['required','string','exists:fixed_asset_transfer_with_specs,id'],
                    'rate'  =>  ['required','numeric'],
                    'qty'   =>  ['required','numeric'],
                    'purpose'=> ['sometimes','nullable','string'],
                    'remarks'=> ['sometimes','nullable','string'],
                ]);
                extract($inputData);
                $update_data = $this->getFixedAssetGpSpecificationAll($permission)->where('id', $id)->first();
                if ($update_data)
                {
                    $update_data->update([
                        'date' => $gp_date,
                        'rate' => $rate,
                        'qty' => $qty,
                        'purpose' => $purpose,
                        'remarks' => $remarks,
                        'updated_by' => $this->user->id,
                        'updated_at' => \Carbon\Carbon::now(),
                    ]);
                    // Refresh the model instance to get the updated data
                    $update_data = $update_data->fresh();
                }
                $transferData = $this->getFixedAssetGpAll($permission)->where('reference',$update_data->reference)->orderBy('created_at','DESC')->first();
                $view = view('back-end/asset/transfer/__list_table_only',compact('transferData'))->render();
                return response()->json([
                    'status'=>'success',
                    'data'=>['view' => $view,'data'=>$update_data],
                    'message'=>'Data updated successfully.'
                ]);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }

    public function finalUpdateTransfer(Request $request)
    {
        $this->store($request);
    }
    public function store(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $permission = $this->permissions()->fixed_asset_transfer_entry;
                $inputData = $request->validate([
                    'from_company' => ['string', 'required', 'exists:company_infos,id'],
                    'to_company' => ['string', 'required', 'exists:company_infos,id'],
                    'from_project' => ['string', 'required', 'exists:branches,id'],
                    'to_project' => ['string', 'required', 'exists:branches,id'],
                    'gp_date' => ['date', 'date_format:d-M-y'],
                    'reference' => ['required','string','exists:fixed_asset_transfers,reference'],
                    'narration' => ['sometimes','nullable','string'],
                    'attachments' => ['sometimes','sometimes', 'array'],
                    'attachments.*' => ['nullable','file','mimes:jpg,jpeg,png,gif,pdf,doc,docx','max:512000'],
                ]);
                extract($inputData);
                $update_data = $this->getFixedAssetGpAll($permission)->where('reference', $reference)->update([
                    'status' => 1,
                    'narration' => $narration,
                    'updated_by' => $this->user->id,
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
                if ($update_data)
                {
                    if ($request->hasFile('attachments'))
                    {
                        $res = $this->fixed_asset_transfer_documents($request->file('attachments'),$this->getFixedAssetGpAll($permission)->where('reference', $reference)->first()->id,$permission);
                        if ($res)
                        {
                            return response()->json([
                                'status' => 'success',
                                'message'=>'Data updated successfully with attachments.',
                            ]);
                        }
                        return response()->json([
                            'status' => 'error',
                            'message'=>'Data updated successfully without attachments.',
                        ]);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message'=>'Data updated successfully.'
                    ]);
                }
                return response()->json([
                    'status'=>'error',
                    'message'=>'There was an error processing your request. Please try again.'
                ]);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }
    protected function fixed_asset_transfer_documents($documents,$ref_id,$operation_name)
    {
        try {
            $id = $ref_id;
            $f_a_t_b = Fixed_asset_transfer::where('id', $id)->first();
            foreach ($documents as $file)
            {
                $fileName = "GP_".$f_a_t_b->id."_".$file->getClientOriginalName();
                $file_location = $file->move($this->document_path,$fileName); // Adjust the storage path as needed
                if (!$file_location)
                {
                    return redirect()->back()->with('error', 'Documents uploaded error.');
                }
                Fixed_asset_transfer_document::create([
                    'from_company_id'=>$f_a_t_b->from_company_id,
                    'to_company_id'=>$f_a_t_b->to_company_id,
                    'transfer_id'=>$f_a_t_b->id,
                    'document_name'=>$fileName,
                    'document_url'=>$this->document_path,
                    'created_by'=>$this->user->id,
                ]);
            }
            return Fixed_asset_transfer_document::where('transfer_id',$f_a_t_b->id)->get();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    protected function delete_fixed_asset_transfer_balance_spec($id)
    {
        try {
            $data = Fixed_asset_transfer_with_spec::where('id', $id)->first();
            if ($data) {
                Fixed_asset_transfer_with_spec_delete_history::create([
                    'old_id'=>$data->id,
                    'date'=>$data->date,
                    'transfer_id'=>$data->transfer_id,
                    'reference'=>$data->reference,
                    'asset_id'=>$data->asset_id,
                    'spec_id'=>$data->spec_id,
                    'rate'=>$data->rate,
                    'qty'=>$data->qty,
                    'purpose'=>$data->purpose,
                    'remarks'=>$data->remarks,
                    'created_by'=>$data->created_by,
                    'updated_by'=>$data->updated_by,
                    'old_created_at'=>$data->created_at,
                    'old_updated_at'=>$data->updated_at,
                    'deleted_by'=>$this->user->id
                ]);
                return $data->delete();
            }
            return false;
        }catch (\Throwable $exception)
        {
            return $exception;
        }
    }
    public function materialWiseSpecification(Request $request)
    {
        try {
            if ($request->isMethod('POST')) {
                $permission = $this->permissions()->fixed_asset_transfer_entry;
                $data = $request->validate([
                    'from_company_id' => ['sometimes','string', 'required', 'exists:company_infos,id'],
                    'from_branch_id' => ['sometimes','string', 'required', 'exists:branches,id'],
                    'materials_id'  =>  ['sometimes','string', 'required', 'exists:fixed_assets,id'],
                ]);
                extract($data);
                $fixed_asset_specification_ids  = $this->getFixedAssetStockMaterialSpecifications($permission,[$materials_id],[$from_branch_id],$from_company_id);
                $fixed_asset_specifications = $this->getFixedAssetSpecification($permission)->whereIn('id',$fixed_asset_specification_ids)->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $fixed_asset_specifications,
                    'message' => 'Data process successfully.'
                ]);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request method not allowed.'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function materialSpecificationWiseStockRate(Request $request)
    {
        try {
            if ($request->isMethod('POST')) {
                $permission = $this->permissions()->fixed_asset_transfer_entry;
                $data = $request->validate([
                    'from_company_id' => ['sometimes','string', 'required', 'exists:company_infos,id'],
                    'from_branch_id' => ['sometimes','string', 'required', 'exists:branches,id'],
                    'materials_id'  =>  ['sometimes','string', 'required', 'exists:fixed_assets,id'],
                    'spec_id'  =>  ['sometimes','string', 'required', 'exists:fixed_asset_specifications,id'],
                ]);
                extract($data);
                $stock = $this->getFixedAssetSpecificationWiseStockBalance($permission,$spec_id,[$materials_id],[$from_branch_id],$from_company_id);
                $rate = $this->getFixedAssetSpecificationWiseRate($permission,$spec_id,[$materials_id],[$from_branch_id],$from_company_id);
                return response()->json([
                    'status' => 'success',
                    'data' => ['stock'=>$stock,'rate'=>$rate],
                    'message' => 'Data process successfully.'
                ]);
            }
            return response()->json([
                'status'=>'error',
                'message'=>'Request method not allowed.'
            ]);
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    protected function delete_fixed_asset_transfer_balance($id)
    {
        try {
            $data = Fixed_asset_transfer::where('id', $id)->first();
            if ($data) {
                $insert = Fixed_asset_transfer_delete_history::create([
                    'old_id'=>$data->id,
                    'date'=>$data->date,
                    'reference'=>$data->reference,
                    'from_company_id'=>$data->from_company_id,
                    'to_company_id'=>$data->to_company_id,
                    'from_branch_id'=>$data->from_project_id,
                    'to_branch_id'=>$data->to_project_id,
                    'status'=>$data->status,
                    'created_by'=>$data->created_by,
                    'updated_by'=>$data->updated_by,
                    'narration'=>$data->narration,
                    'old_created_at'=>$data->created_at,
                    'old_updated_at'=>$data->updated_at,
                    'deleted_by'=>$this->user->id
                ]);
                if ($insert) {
                    return (bool)$data->delete();
                }
            }
            return false;
        }catch (\Throwable $exception)
        {
            return false;
        }
    }
    public function deleteFixedAssetDocuments($transfer_id)
    {
        try {
            $documents = Fixed_asset_transfer_document::where('transfer_id', $transfer_id)->get();
            if ($documents && count($documents)) {
                foreach ($documents as $document) {
                    Fixed_asset_transfer_document_delete_history::create([
                        'old_id'=>$document->id,
                        'from_company_id'=>$document->from_company_id,
                        'to_company_id'=>$document->to_company_id,
                        'transfer_id'=>$document->transfer_id,
                        'document_name'=>$document->document_name,
                        'document_url'=>$document->document_url,
                        'created_by'=>$document->created_by,
                        'updated_by'=>$document->updated_by,
                        'deleted_by'=>$this->user->id,
                        'old_created_at'=>$document->created_at,
                        'old_updated_at'=>$document->updated_at,
                        'created_at'=>now(),
                    ]);
                }
                return (bool)$documents->delete();
            }
        }catch (\Throwable $exception)
        {
            return false;
        }
    }
    public function deleteFixedAssetRunningTransfer(Request $request)
    {
        try {
            $permission = $this->permissions()->delete_fixed_asset_transfer;
            if ($request->isMethod('delete'))
            {
                $validatedData = $request->validate([
                    'id' => ['required','string','exists:fixed_asset_transfers,id'],
                ]);
                extract($validatedData);
                $deleteData = $this->getFixedAssetGpAll($permission)->where('id', $id)->first();
                if ($deleteData) {
                    if ($deleteData->status != 0) {
                        if (!$this->user->hasPermission('delete_fixed_asset_transfer')) {
                            return response()->json([
                                'status'=>'error',
                                'message'=>'You do not have permission to delete this fixed asset transfer.'
                            ]);
                        }
                    }
                    if (count($deleteData->specifications))
                    {
                        foreach ($deleteData->specifications as $specification)
                        {
                            $this->delete_fixed_asset_transfer_balance_spec($specification->id);
                        }
                    }
                    if (count($deleteData->documents))
                    {
                        $this->deleteFixedAssetDocuments($deleteData->id);
                    }
                    $deleteTransfer = $this->delete_fixed_asset_transfer_balance($deleteData->id);
                    if ($deleteTransfer)
                    {
                        return \response()->json([
                            'status'=>'success',
                            'message'=>'Data deleted successfully.'
                        ]);
                    }
                    return \response()->json([
                        'status'=>'error',
                        'message'=>'Failed to delete data'
                    ]);
                }
                return \response()->json([
                    'status'=>'error',
                    'message'=>'Data not found!'
                ]);
            }return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ]);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ]);
        }
    }

    public function edit(Request $request,$fatid)
    {
        try {
            $permission = $this->permissions()->edit_fixed_asset_transfer;
            $id = Crypt::decryptString($fatid);
            $item = $this->getFixedAssetGpAll($permission)->where('id',$id)->first();
            $projects = $this->getUserProjectPermissions($this->user->id,$permission)->where('company_id',$item->from_company_id)->get();
            if (count($projects) <= 0)
            {
                return back()->with('error','You do not have permission for from company.');
            }
            $fixed_assets = $this->getFixedAssets($permission)->where('status',1)->where('company_id',$item->from_company_id)->get();
            $spec = $this->getFixedAssetSpecification($permission)->where('status',1)->where('company_id',$item->from_company_id)->get();
            $companies = $this->getCompany()->get();
            if ($item && $request->isMethod('PUT'))
            {
                return $this->updateFixedAssetTransfer($request, $item);
            }
            if ($item)
            {
                $view = view('back-end.asset.transfer.edit.edit-fixed-asset-transfer',compact('id','projects','fixed_assets','spec','item','companies'))->render();
                return $view;
            }
            return back()->with('error','Materials not found!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    private function updateFixedAssetTransfer(Request $request,$item)
    {
        try {
            $permission = $this->permissions()->edit_fixed_asset_transfer;
            $inputData = $request->validate([
                'from_company_id' => ['required','string', 'exists:company_infos,id','exists:fixed_asset_transfers,from_company_id'],
                'to_company_id' => ['required','string', 'exists:company_infos,id','exists:fixed_asset_transfers,to_company_id'],
                'from_branch_id' => ['required','string', 'exists:branches,id','exists:fixed_asset_transfers,from_project_id'],
                'to_branch_id' => ['required','string', 'exists:branches,id','exists:fixed_asset_transfers,to_project_id'],
                'gp_reference' => ['required','string','exists:fixed_asset_transfers,reference'],
                'gp_date' => ['sometimes','date', 'date_format:Y-m-d'],
                'narration' => ['sometimes','nullable','string'],
                'attachments' => ['sometimes','sometimes', 'array'],
                'attachments.*' => ['nullable','file','mimes:jpg,jpeg,png,gif,pdf,doc,docx','max:512000'],
            ]);
            extract($inputData);
            if ($item)
            {
                $update = Fixed_asset_transfer::where('id',$item->id)->update([
                    'date' => date('Y-m-d H:i:s' , strtotime($gp_date)),
                    'reference' => $gp_reference,
                    'from_company_id' => $from_company_id,
                    'from_project_id' => $from_branch_id,
                    'to_company_id' => $to_company_id,
                    'to_project_id' => $to_branch_id,
                    'narration' => $narration,
                    'updated_by' => $this->user->id,
                    'updated_at' => now(),
                ]);
                if ($update)
                {
                    if ($item->reference != $gp_reference)
                    {
                        Fixed_asset_transfer_with_spec::where('transfer_id',$item->id)->where('reference',$item->reference)->update([
                            'reference' => $gp_reference,
                        ]);
                    }
                    if ($request->hasFile('attachments'))
                    {
                        $res = $this->fixed_asset_transfer_documents($request->file('attachments'),$item->id,$permission);
                        if (!$res)
                        {
                            return response()->json([
                                'status' => 'success',
                                'message'=>'Attachments upload failed! But Data update successfully.',
                            ]);
                        }
                    }
                    $this->updatedHistory($item,$gp_reference);
                    return back()->with('success','Data updated successfully!');
                }
                return back()->with('error','Data update failed!');
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    protected function updatedHistory($itemData,$ref)
    {
        try {
            if ($itemData)
            {
                return Fixed_asset_transfer_edit_history::create([
                    'old_id' => $itemData->id,
                    'status'=> $itemData->status,
                    'date' => date('Y-m-d H:i:s' , strtotime($itemData->date)),
                    'reference' => @$ref,
                    'old_reference' => $itemData->reference,
                    'from_company_id' => $itemData->from_company_id,
                    'from_project_id' => $itemData->from_project_id,
                    'to_company_id' => $itemData->to_company_id,
                    'to_project_id' => $itemData->to_project_id,
                    'narration' => $itemData->narration,
                    'created_by' => $itemData->created_by,
                    'updated_by' => $itemData->updated_by,
                    'old_created_at' => $itemData->created_at,
                    'old_updated_at' => $itemData->updated_at,
                    'modified_by' => $this->user->id,
                ]);
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function destroyDocument(Request $request)
    {
        try {
            $request->validate([
                'id' => ['string','required','exists:fixed_asset_opening_balance_documents,id'],
            ]);
            extract($request->post());
            $document = Fixed_asset_transfer_document::where('id',$id)->first();
            Fixed_asset_transfer_document_delete_history::create([
                'old_id'=>$document->id,
                'from_company_id'=>$document->from_company_id,
                'to_company_id'=>$document->to_company_id,
                'transfer_id'=>$document->transfer_id,
                'document_name'=>$document->document_name,
                'document_url'=>$document->document_url,
                'created_by'=>$document->created_by,
                'updated_by'=>$document->updated_by,
                'deleted_by'=>$this->user->id,
                'old_created_at'=>$document->created_at,
                'old_updated_at'=>$document->updated_at,
                'created_at'=>now(),
            ]);
            $document->delete();
            return response()->json(['status'=>'success','message'=>'Data deleted successfully.']);
        }catch (\Throwable $exception)
        {
            return response()->json(['status'=>'error','message'=>$exception->getMessage()],200);
        }
    }

    public function fixedAssetTransferPrint($assetID)
    {
        $permission = $this->permissions()->fixed_asset_transfer;
        $id = Crypt::decryptString($assetID);
        $transferData = $this->getFixedAssetGpAll($permission)->where('id', $id)->first();
//            dd($withRefData->branch);
        if (!$transferData) {
            abort(404); // or handle the not found case as needed
        }
        $view = view('back-end.asset.transfer.edit.fixed-asset-transfer-print',compact('transferData'))->render();
        //required to pdf "C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"
        $pdf = SnappyPdf::loadView('back-end.asset.transfer.edit.fixed-asset-transfer-print',compact('transferData'))->setPaper('a4','landscape');
        return $view;
//            return $pdf->download("salary_certificate_for_{$withRefData->refType->name}_{$withRefData->references}.pdf");
    }
}
