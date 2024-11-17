<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Fixed_asset;
use App\Models\Fixed_asset_opening_balance;
use App\Models\Fixed_asset_opening_balance_delete_history;
use App\Models\Fixed_asset_opening_balance_document;
use App\Models\Fixed_asset_opening_with_spec;
use App\Models\Fixed_asset_opening_with_spec_delete_history;
use App\Models\fixed_asset_specifications;
use App\Models\Fixed_asset_with_ref_document_delete_history;
use App\Models\Op_reference_type;
use App\Models\userProjectPermission;
use App\Traits\ParentTraitCompanyWise;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Intervention\Image\Response;
use Throwable;
use function PHPUnit\Framework\isEmpty;

class FixedAssetDistribution extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    private $document_path = "documents/fixed-asset/";
    protected function fixedAssetOpeningBalances($permission)
    {
        try {
            $projectIds = $this->getUserProjectPermissions($this->user->id,$permission)->pluck('id')->unique()->toArray();
            return Fixed_asset_opening_balance::with(['withSpecifications','attestedDocuments','branch','createdBy','updatedBy','refType','company'])->whereIn('branch_id',$projectIds);
        }catch (\Throwable $exception)
        {
            return $exception;
        }
    }
    protected function refTypes()
    {
        return Op_reference_type::where('status',1);
    }
    protected function fixedAssets($permission)
    {
        return $this->getFixedAssets($permission)->where('status',1);
    }
    protected function fixedAssetSpecifications($fixed_asset_id)
    {
        return fixed_asset_specifications::with(['fixed_asset'])->where('fixed_asset_id',$fixed_asset_id)->where('status',1)->where('company_id',$this->user->company_id);
    }
    protected function processFixedAssetOpening(Request $request,$permission, $isApiRequest = false)
    {
        if ($request->isMethod('post'))
        {
            $request->validate([
                'company_id' => ['required','string','exists:company_infos,id'],
                'reference'=>['sometimes','nullable','string',],
                'branch_id'=>['sometimes','nullable','string',Rule::exists('branches','id')->where(function ($query) use ($request) {$query->where('status',1);})],
                'r_type_id'=>['sometimes','nullable','string',Rule::exists('op_reference_types','id')->where(function ($query) use ($request) {$query->where('status',1);})],
            ]);
            extract($request->post());
        }
        else{
            $reference = $request->get('ref');
            $branch_id = $request->get('project');
            $r_type_id = $request->get('rt');
            $company_id = $request->get('c');
        }
        $projects = null;
        $ref_types = null;
        if (!empty($company_id))
        {
            $projects = $this->getUserProjectPermissions($this->user->id,$permission)->where('company_id',$company_id)->get();
            $ref_types = $this->refTypes()->where('company_id',$company_id)->get();
        }

        $companies = $this->getCompanyModulePermissionWise($permission)->get();
        $company = $this->getCompany()->where('id',$company_id)->first();
        if (empty($company_id) && empty($reference) && empty($branch_id) && empty($r_type_id))
        {
            if ($isApiRequest)
            {
                return \response()->json([
                    'status'=>'error',
                    'message'=>'Empty field error!'
                ]);
            }
            return view('back-end.asset.fixed-asset-opening',compact('projects','companies','ref_types'));
        }
        else{
            $fixed_asset_with_ref = $this->getFixedAssetWithRefData($permission)->where('company_id',$company_id);
            if (!empty($reference))
            {
                $withRefData = $fixed_asset_with_ref->where('references',$reference)->first();
                $fixed_assets = $this->fixedAssets($permission)->where('company_id',$company_id)->get();
                if (empty($branch_id) || empty($r_type_id))
                {
                    if ($isApiRequest)
                    {
                        return \response()->json([
                            'status'=>'error',
                            'message'=>'Project or Reference Type are required.'
                        ],200);
                    }
                    return redirect()->back()->with('error','Project or Reference Type are required.');
                }
                else{
                    if ($withRefData && ($withRefData->ref_type_id != $r_type_id || $withRefData->branch_id != $branch_id))
                    {
                        if ($isApiRequest)
                        {
                            return \response()->json([
                                'status'=>'error',
                                'message'=>'This reference number already exists for Project: [.'.$withRefData->branch->branch_name. '] and Reference Type is ['.$withRefData->refType->name.']. Please select the correct project or Reference Type.'
                            ],200);
                        }
                        return redirect()->back()->with('error','This reference number already exists for Project: [.'.$withRefData->branch->branch_name. '] and Reference Type is ['.$withRefData->refType->name.']. Please select the correct project or Reference Type.');
                    }
                }
                if ($withRefData && $withRefData->status == 5)
                {
                    $for_project_id = $withRefData->branch_id;
                    $ref_type_id = $withRefData->ref_type_id;
                    $ref_type_name = $withRefData->refType->name;
                    $for_project_name = $withRefData->branch->branch_name;
                    //Input Field
                    if ($isApiRequest)
                    {
                        $view =  view('back-end.asset._fixed_asset_opening_body',compact('fixed_assets','withRefData','reference','for_project_id','ref_type_id','ref_type_name','for_project_name','companies','company'))->render();
                    }
                    else{
                        $view =  view('back-end.asset.fixed-asset-opening',compact('projects','fixed_assets','ref_types','withRefData','reference','for_project_id','ref_type_id','ref_type_name','for_project_name','companies','company'))->render();
                    }
                }
                else if (empty($withRefData))
                {
                    $ref_type_name = $this->refTypes()->where('id',$r_type_id)->first()->name;
                    $for_project_name = branch::where('id',$branch_id)->first()->branch_name;
                    $for_project_id = $branch_id;
                    $ref_type_id = $r_type_id;
                    if ($isApiRequest)
                    {
                        $view = view('back-end.asset._fixed_asset_opening_body',compact('fixed_assets','withRefData','reference','for_project_id','ref_type_id','ref_type_name','for_project_name','companies','company'))->render();
                    }
                    else{
                        $view = view('back-end.asset.fixed-asset-opening',compact('projects','fixed_assets','ref_types','withRefData','reference','for_project_id','ref_type_id','ref_type_name','for_project_name','companies','company'))->render();
                    }
                }
                else{
                    // Reporting--------------
                    $fixed_asset_with_ref_report_list = $fixed_asset_with_ref->where('references',$reference)->get();
                    if ($isApiRequest)
                    {
                        $view = view('back-end.asset._fixed_asset_opening_project_wise_list',compact('fixed_asset_with_ref_report_list','companies'))->render();
                    }
                    else{
                        $view = view('back-end.asset.fixed-asset-opening',compact('projects','ref_types','fixed_asset_with_ref_report_list','companies'))->render();
                    }
                }
            }
            else{
                // Reporting--------------
                if (!empty($company_id) && empty($branch_id) && empty($r_type_id))
                {
                    $fixed_asset_with_ref_report_list = $fixed_asset_with_ref->where('company_id',$company_id)->get();
                }
                else if (empty($branch_id) && !empty($r_type_id))
                {
                    $fixed_asset_with_ref_report_list = $fixed_asset_with_ref->where('ref_type_id',$r_type_id)->get();
                }
                else if (!empty($branch_id) && empty($r_type_id))
                {
                    $fixed_asset_with_ref_report_list = $fixed_asset_with_ref->where('branch_id',$branch_id)->get();
                }
                else if (!empty($branch_id) && !empty($r_type_id))
                {
                    $fixed_asset_with_ref_report_list = $fixed_asset_with_ref->where('branch_id',$branch_id)->where('ref_type_id',$r_type_id)->get();
                }
                else{
                    $fixed_asset_with_ref_report_list = $fixed_asset_with_ref->get();
                }

                if ($isApiRequest)
                {
                    $view = view('back-end.asset._fixed_asset_opening_project_wise_list',compact('fixed_asset_with_ref_report_list','companies'))->render();
                }
                else{
                    $view = view('back-end.asset.fixed-asset-opening',compact('projects','ref_types','fixed_asset_with_ref_report_list','companies'))->render();
                }
            }
        }
        if ($isApiRequest) {
            return response()->json([
                'status' => 'success',
                'data' => $view,
                'message' => 'Request processed successfully.',
            ], 200);
        }
        return $view;
    }

    public function openingInput(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            return $this->processFixedAssetOpening($request,$permission, false);
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
    public function getFixedAssetOpening(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            if ($request->isMethod('post')) {
                return $this->processFixedAssetOpening($request, $permission, true);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Request not supported!',
            ], 200);
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 200);
        }
    }


    public function fixedAssetSpecification(Request $request): JsonResponse
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            $request->validate([
                'id'=>['required','string','exists:fixed_assets,id'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $spec = $this->getFixedAssetSpecification($permission)->where('fixed_asset_id',$id)->get();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$spec,
                    'message'=>'Request processed successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],500);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],500);
        }
    }
    public function addFixedAssetOpening(Request $request)
    {
//        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            $request->validate([
                'opening_date'    => ['required','date'],
                'company_id' => ['required','string','exists:company_infos,id',],
                'reference' => ['required','string'],
                'r_type' => ['required','string',Rule::exists('op_reference_types','id')->where(function ($query) use ($request) {$query->where('status',1);})],
                'project_id'=>  ['required','string','exists:branches,id'],
                'materials_id'=>    ['required','string','exists:fixed_assets,id',],
                'specification'=>   ['required','string','exists:fixed_asset_specifications,id'],
                'rate'  =>  ['required','numeric'],
                'qty'   =>  ['required','numeric'],
                'purpose'=> ['sometimes','nullable','string'],
                'remarks'=> ['sometimes','nullable','string'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $opening = $this->getFixedAssetWithRefData($permission)->where('branch_id',$project_id)->where('references',$reference)->first();
                if (is_null($opening) || empty($opening))
                {
                    $opening = Fixed_asset_opening_balance::create([
                        'date'=>$opening_date,
                        'references'=>$reference,
                        'ref_type_id'=>$r_type,
                        'branch_id'=>$project_id,
                        'company_id'=>$company_id,
                        'status'=>5,//status-5 = processing
                        'created_by'=>$this->user->id,
                    ]);
                }
//                if ($opening && $opening->status !=5)
//                {
//                    if ($ref_via_search = Fixed_asset_opening_balance::with(['branch'])->where('references',$reference)->where('company_id',$user->company_id)->first()) {
//                        $msg = 'This reference already exists for ' . $ref_via_search->branch->branch_name;
//                        if ($ref_via_search->branch_id != $project_id) {
//                            $msg = 'Work Processing.......';
//                        }
//                        return \response()->json([
//                            'status' => 'warning',
//                            'message' => $msg,
//                        ], 200);
//                    }
//                }
//                else
//                {
                    $base_table_data = $this->getFixedAssetWithRefData($permission)->where('references',$reference)->first();
                    $opening_materials_obj = $this->getFixedAssetWithRefSpecification($permission)->where('opening_asset_id',$base_table_data->id)->where('references',$base_table_data->references);
                    $omt1 = $opening_materials_obj;
                    if ($omt1->where('asset_id',$materials_id)->where('spec_id',$specification)->count() > 0)
                    {
                        return \response()->json([
                            'status'=>'warning',
                            'attribute'=>'duplicate',
                            'message'=>'Duplicate data found!'
                        ],200);
                    }
                    else{
                        Fixed_asset_opening_with_spec::create([
                            'date'=>$opening_date,
                            'opening_asset_id'=>$base_table_data->id,
                            'references'=>$reference,
                            'company_id'=>$company_id,
                            'asset_id'=>$materials_id,
                            'spec_id'=>$specification,
                            'rate'=>$rate,
                            'qty'=>$qty,
                            'purpose'=>$purpose,
                            'remarks'=>$remarks,
                            'created_by'=>$this->user->id,
                        ]);
                    }
//                }
                $withRefData = $this->getFixedAssetWithRefData($permission)->where('company_id',$company_id)->where('branch_id',$project_id)->where('references',$reference)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset._fixed_asset_opening_body_list',compact('withRefData'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Request processed successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],200);
//        }catch (\Throwable $exception)
//        {
//            return \response()->json([
//                'status'=>'error',
//                'message'=> $exception->getMessage(),
//            ],200);
//        }
    }
    public function editFixedAssetOpeningList(Request $request)
    {
//        try {
            $permission = $this->permissions()->add_fixed_asset;
            $request->validate([
                'opening_date'    => ['required','date'],
                'company_id' => ['required','string','exists:company_infos,id',],
                'reference' => ['required','string'],
                'r_type' => ['required','string',Rule::exists('op_reference_types','id')->where(function ($query) use ($request) {$query->where('status',1);})],
                'project_id'=>  ['required','string','exists:branches,id'],
                'materials_id'=>    ['required','string','exists:fixed_assets,id',],
                'specification'=>   ['required','string','exists:fixed_asset_specifications,id'],
                'rate'  =>  ['required','numeric'],
                'qty'   =>  ['required','numeric'],
                'purpose'=> ['sometimes','nullable','string'],
                'remarks'=> ['sometimes','nullable','string'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $opening = $this->getFixedAssetWithRefData($permission)->where('branch_id',$project_id)->where('references',$reference)->first();
                if (is_null($opening) || empty($opening))
                {
                    $opening = Fixed_asset_opening_balance::create([
                        'date'=>$opening_date,
                        'references'=>$reference,
                        'ref_type_id'=>$r_type,
                        'branch_id'=>$project_id,
                        'company_id'=>$company_id,
                        'status'=>5,//status-5 = processing
                        'created_by'=>$this->user->id,
                    ]);
                }
//                if ($opening && $opening->status !=5)
//                {
//                    if ($ref_via_search = Fixed_asset_opening_balance::with(['branch'])->where('references',$reference)->where('company_id',$user->company_id)->first()) {
//                        $msg = 'This reference already exists for ' . $ref_via_search->branch->branch_name;
//                        if ($ref_via_search->branch_id != $project_id) {
//                            $msg = 'Work Processing.......';
//                        }
//                        return \response()->json([
//                            'status' => 'warning',
//                            'message' => $msg,
//                        ], 200);
//                    }
//                }
//                else
//                {
                    $base_table_data = $this->getFixedAssetWithRefData($permission)->where('references',$reference)->first();
                    $opening_materials_obj = $this->getFixedAssetWithRefSpecification($permission)->where('opening_asset_id',$base_table_data->id)->where('references',$base_table_data->references);
                    $omt1 = $opening_materials_obj;
                    if ($omt1->where('asset_id',$materials_id)->where('spec_id',$specification)->count() > 0)
                    {
                        return \response()->json([
                            'status'=>'warning',
                            'attribute'=>'duplicate',
                            'message'=>'Duplicate data found!'
                        ],200);
                    }
                    else{
                        Fixed_asset_opening_with_spec::create([
                            'date'=>$opening_date,
                            'opening_asset_id'=>$base_table_data->id,
                            'references'=>$reference,
                            'company_id'=>$company_id,
                            'asset_id'=>$materials_id,
                            'spec_id'=>$specification,
                            'rate'=>$rate,
                            'qty'=>$qty,
                            'purpose'=>$purpose,
                            'remarks'=>$remarks,
                            'created_by'=>$this->user->id,
                        ]);
                    }
//                }
                $withRefData = $this->getFixedAssetWithRefData($permission)->where('company_id',$company_id)->where('branch_id',$project_id)->where('references',$reference)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset.__edit_fixed_asset_opening_body_list',compact('withRefData'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Request processed successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],200);
//        }catch (\Throwable $exception)
//        {
//            return \response()->json([
//                'status'=>'error',
//                'message'=> $exception->getMessage(),
//            ],200);
//        }
    }

    public function editFixedAssetOpening(Request $request,$faobid)
    {
        try {
            $permission = $this->permissions()->edit_fixed_asset_distribution_with_reference;
            $id = Crypt::decryptString($faobid);
            $item = $this->getFixedAssetWithRefData($permission)->where('id',$id)->first();
            $projects = $this->getUserProjectPermissions($this->user->id,$permission)->get();
//            $projects = $this->getUserWiseProjects($this->user->id);
            $ref_types = $this->refTypes()->get();
            $fixed_assets = $this->FixedAssets($permission)->where('company_id',$item->company_id)->get();
            $spec = $this->fixedAssetSpecifications($id)->get();
            $companies = $this->getCompany()->get();
            if ($request->isMethod('PUT'))
            {
                return $this->updateFixedAssetOpening($request, $id);
            }
            $view = view('back-end.asset.edit-fixed-asset-opening',compact('id','projects','ref_types','fixed_assets','spec','item','companies'))->render();
            return $view;

        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    protected function updateFixedAssetOpening(Request $request,$id)
    {
        try {
            $permission = $this->permissions()->edit_fixed_asset_distribution_with_reference;
            $request->validate([
                'company_id' => ['required','string','exists:company_infos,id'],
                'reference' => ['required','string',Rule::unique('fixed_asset_opening_balances','references')->ignore($id, 'id')],
                'r_type' => ['required','string',Rule::exists('op_reference_types','id')->where(function ($query) use ($request) {$query->where('status',1);})],
                'project_id'=>  ['required','string','exists:branches,id'],
                'attachment.*' => ['sometimes','nullable','file','max:512000'],
                'narration' => ['sometimes','nullable','string'],
            ]);
            extract($request->post());
            $data = $this->getFixedAssetWithRefData($permission)->where('id',$id)->first();
            $update = $this->getFixedAssetWithRefData($permission)->where('id',$id)->update([
                'company_id'=>$company_id,
                'references'=>$reference,
                'ref_type_id'=>$r_type,
                'branch_id'=>$project_id,
                'narration'=>$narration,
                'updated_by'=>$this->user->id,
                'updated_at'=>date('Y-m-d H:i:s')
            ]);
            if ($data->references != $reference)
            {
                Fixed_asset_opening_with_spec::where('opening_asset_id',$id)->update([
                    'references'=>$reference,
                    'updated_by'=>$this->user->id,
                    'updated_at'=>date('Y-m-d H:i:s')
                ]);
            }
            if ($data->company_id != $company_id)
            {
                Fixed_asset_opening_with_spec::where('opening_asset_id',$id)->update([
                    'company_id'=>$company_id,
                    'updated_by'=>$this->user->id,
                    'updated_at'=>date('Y-m-d H:i:s')
                ]);
            }
            if ($update)
            {
                if ($request->hasFile('attachment'))
                {
                    $res = $this->fixed_asset_opening_balance_documents($request->file('attachment'),$id,$permission);
                    if ($res)
                    {
                        return back()->with('success','Data updated successfully.');
                    }
                    return back()->with('warning','Data updated successfully. But some error occurred to upload documents.');
                }
            }
            return back()->with('success','Data updated successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function editFixedAssetOpeningSpec(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            $request->validate([
                'id'=>['required',Rule::exists('fixed_asset_opening_with_specs','id')],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $data = $this->getFixedAssetWithRefSpecification($permission)->whereId($id)->first();
//                $data = Fixed_asset_opening_with_spec::with(['asset','specification'])->where('company_id',$this->user->company_id)->whereId($id)->first();
                $view = view('back-end.asset.__edit_fixed_asset_ope_spec',compact('data'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Request processed successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],500);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],500);
        }
    }

    public function updateFixedAssetOpeningSpec(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            $request->validate([
                'opening_date'    => ['required','date'],
                'id' => ['required','string','exists:fixed_asset_opening_with_specs,id'],
                'rate'  =>  ['required','numeric'],
                'qty'   =>  ['required','numeric'],
                'purpose'=> ['sometimes','nullable','string'],
                'remarks'=> ['sometimes','nullable','string'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $update_data = $this->getFixedAssetWithRefSpecification($permission)->where('id', $id)->first();

                if ($update_data) {
                    $update_data->update([
                        'date' => $opening_date,
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
                $withRefData = $this->getFixedAssetWithRefData($permission)->where('references',$update_data->references)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset.__edit_fixed_asset_opening_body_list',compact('withRefData'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Data update successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],500);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],500);
        }
    }

    public function deleteFixedAssetOpeningSpec(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            $request->validate([
                'id' => ['required','string','exists:fixed_asset_opening_with_specs,id'],
            ]);
            if ($request->isMethod('delete'))
            {
                extract($request->post());
                $update_data = Fixed_asset_opening_with_spec::where('id', $id)->first();
                $reference = $update_data->references;
                if ($update_data) {
                    $this->delete_fixed_asset_opening_balance_spec($update_data->id);

                    // Refresh the model instance to get the updated data
                    $update_data = $update_data->fresh();
                }
                $withRefData = $this->getFixedAssetWithRefData($permission)->where('references',$reference)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset.__edit_fixed_asset_opening_body_list',compact('withRefData'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Data deleted successfully.'
                ],200);
            }
            return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],500);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],500);
        }
    }
    protected function delete_fixed_asset_opening_balance_spec($id)
    {
        try {
            $data = Fixed_asset_opening_with_spec::where('id', $id)->first();
            if ($data) {
                Fixed_asset_opening_with_spec_delete_history::create([
                    'old_id'=>$data->id,
                    'date'=>$data->date,
                    'opening_asset_id'=>$data->opening_asset_id,
                    'references'=>$data->references,
                    'company_id'=>$data->company_id,
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
    protected function delete_fixed_asset_opening_balance($id)
    {
        try {
            $data = Fixed_asset_opening_balance::where('id', $id)->first();
            if ($data) {
                Fixed_asset_opening_balance_delete_history::create([
                    'old_id'=>$data->id,
                    'date'=>$data->date,
                    'references'=>$data->references,
                    'ref_type_id'=>$data->ref_type_id,
                    'branch_id'=>$data->branch_id,
                    'company_id'=>$data->company_id,
                    'status'=>$data->status,
                    'created_by'=>$data->created_by,
                    'updated_by'=>$data->updated_by,
                    'narration'=>$data->narration,
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
    public function deleteFixedAssetOpening(Request $request)
    {
        try {
            $permission = $this->permissions()->delete_fixed_asset_opening_balance;
            $request->validate([
                'id' => ['required','string','exists:fixed_asset_opening_balances,id'],
            ]);
            if ($request->isMethod('delete'))
            {
                extract($request->post());
                $deleteData = $this->getFixedAssetWithRefData($permission)->where('id', $id)->first();
                if ($deleteData) {
                    if (count($deleteData->withSpecifications))
                    {
                        foreach ($deleteData->withSpecifications as $specification)
                        {
                            $this->delete_fixed_asset_opening_balance_spec($specification->id);
                        }
                    }
                    $this->delete_fixed_asset_opening_balance($deleteData->id);
                    return \response()->json([
                        'status'=>'success',
                        'message'=>'Data deleted successfully.'
                    ],200);
                }
                return \response()->json([
                    'status'=>'error',
                    'message'=>'Data deleted not possible.'
                ],200);
            }return \response()->json([
                'status'=>'error',
                'message'=>'Request not supported!'
            ],500);
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],500);
        }
    }
    public function finalUpdateFixedAssetOpeningSpec(Request $request): Redirector|RedirectResponse|Application
    {
        try {
            $permission = $this->permissions()->fixed_asset_with_reference_input;
            $request->validate([
                'id' => ['required','string',Rule::exists('fixed_asset_opening_balances','id')->where('status',5)],
                'attachment.*' => ['sometimes','nullable','file','mimes:jpg,jpeg,png,gif,pdf,doc,docx','max:512000'],
            ]);
            if ($request->isMethod('put'))
            {
                extract($request->post());

                if ($this->getFixedAssetWithRefSpecification($permission)->where('opening_asset_id',$id)->count() <= 0)
                {
                    return back()->with('warning','There is no fixed asset opening balances for this reference.');
                }
                $update = Fixed_asset_opening_balance::where('id',$id)->update([
                    'narration'=>$narration,
                    'status'=>1,//1=active
                    'updated_by'=>$this->user->id,
                    'updated_at'=>now(),
                ]);
                if ($update)
                {
                    if ($request->hasFile('attachment'))
                    {
                        $res = $this->fixed_asset_opening_balance_documents($request->file('attachment'),$id,$permission);
                        if ($res)
                        {
                            return back()->with('success','Data updated successfully.');
                        }
                        return back()->with('warning','Data updated successfully. But some error occurred to upload documents.')->withInput();
                    }
                }
                return redirect(route('fixed.asset.distribution.opening.input'))->with('success','Data final update successfully.');
            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
        return back()->with('error','Request not supported!');
    }
    protected function fixed_asset_opening_balance_documents($documents,$ref_id,$operation_name)
    {
        try {
            $id = $ref_id;
            $f_a_o_b = Fixed_asset_opening_balance::with(['refType'])->where('id', $id)->first();
            foreach ($documents as $file)
            {
                $fileName = $f_a_o_b->refType->name."_".$f_a_o_b->references."_".$file->getClientOriginalName();
                $file_location = $file->move($this->document_path,$fileName); // Adjust the storage path as needed
                if (!$file_location)
                {
                    return redirect()->back()->with('error', 'Documents uploaded error.');
                }
                Fixed_asset_opening_balance_document::create([
                    'company_id'=>$f_a_o_b->company_id,
                    'opening_asset_id'=>$f_a_o_b->id,
                    'document_name'=>$fileName,
                    'document_url'=>$this->document_path,
                    'created_by'=>$this->user->id,
                ]);
            }
            return Fixed_asset_opening_balance_document::whereIn('company_id',$this->getCompanyModulePermissionWiseArray($operation_name))->where('opening_asset_id',$id)->get();
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function printFixedAssetWithReference($assetID)
    {
        try {
            $id = Crypt::decryptString($assetID);
            $withRefData = Fixed_asset_opening_balance::with(['withSpecifications','branch','refType','createdBy','updatedBy','company'])->where('id', $id)->first();
//            dd($withRefData->branch);
            if (!$withRefData) {
                abort(404); // or handle the not found case as needed
            }
            $view = view('back-end.asset.fixed-asset-opening-print',compact('withRefData'))->render();
            //required to pdf "C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe"
            $pdf = SnappyPdf::loadView('back-end.asset.fixed-asset-opening-print',compact('withRefData'))->setPaper('a4','landscape');
//            return $pdf->download("salary_certificate_for_{$withRefData->refType->name}_{$withRefData->references}.pdf");
            return $view;
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function destroyWithRefDocument(Request $request)
    {
        try {
            $request->validate([
                'id' => ['string','required','exists:fixed_asset_opening_balance_documents,id'],
            ]);
            extract($request->post());
            $data = Fixed_asset_opening_balance_document::where('id',$id)->first();
            Fixed_asset_with_ref_document_delete_history::create([
                'old_id' => $data->id,
                'company_id' => $data->company_id,
                'opening_asset_id' => $data->opening_asset_id,
                'document_name' => $data->document_name,
                'document_url' => $data->document_url,
                'created_by' => $data->created_by,
                'updated_by' => $data->updated_by,
                'old_created_at' => $data->created_at,
                'old_updated_at' => $data->updated_at,
                'deleted_by'  => $this->user->id,
            ]);
            $data->delete();
            return response()->json(['status'=>'success','message'=>'Data deleted successfully.']);
        }catch (\Throwable $exception)
        {
            return response()->json(['status'=>'error','message'=>$exception->getMessage()],200);
        }
    }

    public function companyProjects(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                    'user_id' => ['required','string','exists:users,id'],
                ]);
                extract($request->post());
                $op_ref_type = Op_reference_type::where('company_id',$company_id)->where('status',1)->get();
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
                    'data' => ['project'=>$projects,'op_ref_type'=>$op_ref_type],
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
}
