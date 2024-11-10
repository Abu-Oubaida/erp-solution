<?php

namespace App\Http\Controllers;

use App\Models\fixed_asset_specifications;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FixedAssetTransferController extends Controller
{
    use ParentTraitCompanyWise;
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
            if ($request->get('from_p') && $request->get('from_c'))
            {
                $from_projects = $this->getBranch($permission)->where('company_id', $request->get('from_c'))->get();
            }
            if ($request->get('to_p') && $request->get('to_c'))
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
                $permission = $this->permissions()->fixed_asset_transfer_entry;
                $data = $request->validate([
                    'from_company_id' => ['sometimes','string', 'required', 'exists:company_infos,id'],
                    'to_company_id' => ['sometimes','string', 'required', 'exists:company_infos,id'],
                    'from_branch_id' => ['sometimes','string', 'required', 'exists:branches,id'],
                    'to_branch_id' => ['sometimes','string', 'required', 'exists:branches,id'],
                    'gp_reference' => ['sometimes','string', Rule::unique('fixed_asset_transfers','reference')->where('from_company_id',$request->post('from_company_id'))],
                    'gp_date' => ['sometimes','date', 'date_format:Y-m-d'],
                ]);
                extract($data);
                $companies = $this->getCompanyModulePermissionWise($permission);
                $branches = $this->getBranch($permission);
                $from_company = $this->getCompanyModulePermissionWise($permission)->where('id',$from_company_id)->first();
                $to_company = $this->getCompanyModulePermissionWise($permission)->where('id',$to_company_id)->first();
                $from_project = $this->getBranch($permission)->select(['id','branch_name'])->where('company_id',$from_company_id)->where('id',$from_branch_id)->first();
                $to_project = $this->getBranch($permission)->select(['id','branch_name'])->where('company_id',$to_company_id)->where('id',$to_branch_id)->first();
                $fixed_asset_ids  = $this->getFixedAssetStockMaterials($permission,$from_project->id,$from_company->id);

                $fixed_assets = $this->getFixedAssets($permission)->whereIn('id',$fixed_asset_ids)->get();
                $view = view('back-end/asset/transfer/_transfer_body_part_1',compact('fixed_assets','from_company','to_company','from_project','to_project','gp_date','gp_reference'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => $view,
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
                    'gp_date' => ['date', 'date_format:Y-m-d'],
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
                        'data' => date('d-m-Y',strtotime($gp_date)),
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
                    //Need to entry Fixed_asset_transfer_with_specs
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
    public function store(Request $request)
    {

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
                $fixed_asset_specification_ids  = $this->getFixedAssetStockMaterialSpecifications($permission,$materials_id,$from_branch_id,$from_company_id);
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
                $stock = $this->getFixedAssetSpecificationWiseStockBalance($permission,$spec_id,$materials_id,$from_branch_id,$from_company_id);
                $rate = $this->getFixedAssetSpecificationWiseStockRate($permission,$spec_id,$materials_id,$from_branch_id,$from_company_id);
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
}
