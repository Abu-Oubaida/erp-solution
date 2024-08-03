<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Fixed_asset;
use App\Models\Fixed_asset_opening_balance;
use App\Models\Fixed_asset_opening_with_spec;
use App\Models\fixed_asset_specifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Intervention\Image\Response;
use Throwable;
use function PHPUnit\Framework\isEmpty;

class FixedAssetDistribution extends Controller
{
    //
    public function index()
    {

    }

    public function fixedAssetDistribution(Request $request)
    {

    }

    public function openingInput(Request $request)
    {
        try {
            $user = Auth::user();
            $final_opening=null;
            $branchName = null;
            $reference = $request->get('ref');
            $branch = $request->get('project');
            if ($branch !== null || $reference !== null)
            {
                $branch = branch::where('status',1)->where('company_id',$user->company_id)->where('id',$request->get('project'))->first();
                if (is_null($branch))
                {
                    return back()->with('error',"Invalid Project name");
                }
                else{
                    $branchName = $branch->branch_name;
                }

                $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('branch_id',$branch->id)->where('references',$reference)->orderBy('created_at','DESC')->first();
            }
            $projects = branch::where('company_id',$user->company_id)->where('status',1)->get();
            $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status',1)->get();
            if (empty($final_opening) || $final_opening->status == 5)
            {
                if (empty($final_opening) && Fixed_asset_opening_balance::where('references',$reference)->first())
                {
                    return redirect(route('fixed.asset.distribution.opening.input'))->with('warning','Duplicate reference number found!');
                }
                return view('back-end.asset.fixed-asset-opening',compact('projects','fixed_assets','reference','branchName','final_opening'));
            }
            $project_wise_ref = null;
            $project_wise_ref = Fixed_asset_opening_balance::with(['withSpecifications','branch','createdBy','updatedBy'])->where('references',$reference)->where('branch_id',$branch->id)->get();
            return view('back-end.asset.fixed-asset-opening',compact('projects','fixed_assets','reference','branchName','project_wise_ref'));
        }catch (Throwable $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    private function openingStore(Request $request)
    {

    }

    public function getFixedAssetSpecification(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'id'=>['required','string','exists:fixed_assets,id'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $spec = fixed_asset_specifications::with(['fixed_asset'])->where('fixed_asset_id',$id)->where('status',1)->where('company_id',$user->company_id)->get();
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
        try {
            $user = Auth::user();
            $request->validate([
                'opening_date'    => ['required','date'],
                'reference' => ['required','string'],
                'project_id'=>  ['required','string','exists:branches,id'],
                'materials_id'=>    ['required','string','exists:fixed_assets,id'],
                'specification'=>   ['required','string','exists:fixed_asset_specifications,id'],
                'rate'  =>  ['required','numeric'],
                'qty'   =>  ['required','numeric'],
                'purpose'=> ['sometimes','nullable','string'],
                'remarks'=> ['sometimes','nullable','string'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $opening = Fixed_asset_opening_balance::with(['withSpecifications'])->where('company_id',$user->company_id)->where('branch_id',$project_id)->where('references',$reference)->first();
                if (is_null($opening) || empty($opening))
                {
                    $opening = Fixed_asset_opening_balance::create([
                        'date'=>$opening_date,
                        'references'=>$reference,
                        'branch_id'=>$project_id,
                        'company_id'=>$user->company_id,
                        'status'=>5,//status-5 = processing
                        'created_by'=>$user->id,
                    ]);
                }
                if ($opening && $opening->status !=5)
                {
                    if ($ref_via_search = Fixed_asset_opening_balance::with(['branch'])->where('references',$reference)->where('company_id',$user->company_id)->first()) {
                        $msg = 'This reference already exists for ' . $ref_via_search->branch->branch_name;
                        if ($ref_via_search->branch_id == $project_id) {
                            $msg = 'Work Processing.......';
                        }
                        return \response()->json([
                            'status' => 'warning',
                            'message' => $msg,
                        ], 200);
                    }
                }
                else
                {
                    $base_table_data = Fixed_asset_opening_balance::where('company_id',$user->company_id)->firstWhere('references',$reference);
                    $opening_materials_obj = Fixed_asset_opening_with_spec::where('opening_asset_id',$base_table_data->id)->where('references',$base_table_data->references)->where('company_id',$user->company_id);
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
                            'company_id'=>$user->company_id,
                            'asset_id'=>$materials_id,
                            'spec_id'=>$specification,
                            'rate'=>$rate,
                            'qty'=>$qty,
                            'purpose'=>$purpose,
                            'remarks'=>$remarks,
                            'created_by'=>$user->id,
                        ]);
                    }
                }

                $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('branch_id',$project_id)->where('references',$reference)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset._fixed_asset_opening_body_list',compact('final_opening'))->render();
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
        }catch (\Throwable $exception)
        {
            return \response()->json([
                'status'=>'error',
                'message'=> $exception->getMessage(),
            ],500);
        }
    }
    public function getFixedAssetOpening(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'ref'=>['sometimes','nullable','string',],
                'project'=>['sometimes','nullable','string',Rule::exists('branches','id')->where(function ($query) use ($request) {$query->where('status',1);})],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $reference = $ref;
                if (empty($ref))
                {
                    return \response()->json([
                        'status'=>'warning',
                        'message'=>'Work Processing..... when reference is null.',
                    ],200);
                }
                $branchName = $project;
                $fa_opening = Fixed_asset_opening_balance::with(['withSpecifications','branch'])->where('references',$reference)->where('company_id',$user->company_id)->where('branch_id',$project)->first();
                $projects = branch::where('company_id',$user->company_id)->where('status',1)->get();
                $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status',1)->get();
                if ($fa_opening && $fa_opening->status == 5)//processing
                {
                    $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('branch_id',$project)->where('references',$reference)->orderBy('created_at','DESC')->first();
                    $view = view('back-end.asset._fixed_asset_opening_body',compact('final_opening','fa_opening','reference','projects','fixed_assets','branchName'))->render();
                }
                else{
                    if ($ref_via_search = Fixed_asset_opening_balance::with(['branch'])->where('references',$reference)->where('company_id',$user->company_id)->first())
                    {
                        $msg = 'This reference already exists for '.$ref_via_search->branch->branch_name;
                        if ($ref_via_search->branch_id == $project)
                        {
                            $project_wise_ref = Fixed_asset_opening_balance::with(['withSpecifications','branch','createdBy','updatedBy'])->where('references',$reference)->where('branch_id',$project)->get();
                            $view = view('back-end/asset/_fixed_asset_opening_project_wise_list',compact('project_wise_ref'))->render();
                            return \response()->json([
                                'status'=>'success',
                                'data'=>$view,
                                'message'=>'Request processed successfully.'
                            ],200);
                        }
                        return \response()->json([
                            'status'=>'warning',
                            'message'=>$msg,
                        ],200);
                    }
                    $view = view('back-end.asset._fixed_asset_opening_body',compact('fa_opening','reference','projects','fixed_assets','branchName'))->render();
                }

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

    public function editFixedAssetOpeningSpec(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'id'=>['required',Rule::exists('fixed_asset_opening_with_specs','id')],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $data = Fixed_asset_opening_with_spec::with(['asset','specification'])->where('company_id',$user->company_id)->whereId($id)->first();
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
                $user = Auth::user();
                $update_data = Fixed_asset_opening_with_spec::where('id', $id)->first();

                if ($update_data) {
                    $update_data->update([
                        'date' => $opening_date,
                        'rate' => $rate,
                        'qty' => $qty,
                        'purpose' => $purpose,
                        'remarks' => $remarks,
                        'updated_by' => $user->id,
                        'updated_at' => \Carbon\Carbon::now(),
                    ]);

                    // Refresh the model instance to get the updated data
                    $update_data = $update_data->fresh();
                }
                $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('references',$update_data->references)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset._fixed_asset_opening_body_list',compact('final_opening'))->render();
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
            $request->validate([
                'id' => ['required','string','exists:fixed_asset_opening_with_specs,id'],
            ]);
            if ($request->isMethod('delete'))
            {
                extract($request->post());
                $user = Auth::user();
                $update_data = Fixed_asset_opening_with_spec::where('id', $id)->first();
                $reference = $update_data->references;
                if ($update_data) {
                    $update_data->delete();

                    // Refresh the model instance to get the updated data
                    $update_data = $update_data->fresh();
                }
                $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('references',$reference)->orderBy('created_at','DESC')->first();
                $view = view('back-end.asset._fixed_asset_opening_body_list',compact('final_opening'))->render();
                return \response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Data deleted successfully.'
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
    public function finalUpdateFixedAssetOpeningSpec(Request $request)
    {
        $request->validate([
            'id' => ['required','string',Rule::exists('fixed_asset_opening_balances','id')->where('status',5)],
        ]);
        try {
            if ($request->isMethod('put'))
            {
                extract($request->post());
                $user = Auth::user();
                Fixed_asset_opening_balance::where('id',$id)->update([
                    'narration'=>$narration,
                    'status'=>1,//1=active
                ]);
                return redirect(route('fixed.asset.distribution.opening.input'))->with('success','Data final update successfully.');
            }
        }catch (\Throwable $exception)
        {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

}
