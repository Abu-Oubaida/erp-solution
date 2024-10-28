<?php

namespace App\Http\Controllers;

use App\Traits\ParentTraitCompanyWise;
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
    public function index()
    {
        try {
            $permission = $this->permissions()->fixed_asset_transfer_entry;
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.asset.transfer.index',compact('companies'));
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function create(Request $request)
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
                $from_company = $companies->select(['id','company_name'])->where('id',$from_company_id)->first();
                $to_company = $companies->select(['id','company_name'])->where('id',$to_company_id)->first();
                $from_project = $branches->select(['id','branch_name'])->where('company_id',$from_company_id)->where('id',$from_branch_id)->first();
                $to_project = $branches->select(['id','branch_name'])->where('company_id',$to_company_id)->where('id',$to_branch_id)->first();

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
}
