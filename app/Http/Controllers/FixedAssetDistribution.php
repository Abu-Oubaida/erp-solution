<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Fixed_asset;
use App\Models\Fixed_asset_opening_balance;
use App\Models\Fixed_asset_opening_with_spec;
use App\Models\fixed_asset_specifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            if ($request->isMethod('POST')) {

            }
            $user = Auth::user();
            $fa_opening=null;
            $reference = $request->get('ref');
            if ($reference)
                $fa_opening = Fixed_asset_opening_balance::with(['withSpecifications'])->where('references',$reference)->where('company_id',$user->company_id)->first();
            $projects = branch::where('company_id',$user->company_id)->where('status',1)->get();
            $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status',1)->get();
            return view('back-end.asset.fixed-asset-opening',compact('projects','fixed_assets','fa_opening','reference'));
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
                    $opening_base_table = Fixed_asset_opening_balance::create([
                        'date'=>$opening_date,
                        'references'=>$reference,
                        'branch_id'=>$project_id,
                        'company_id'=>$user->company_id,
                        'status'=>5,//status-5 = processing
                        'created_by'=>$user->id,
                    ]);
                }
                else if ($opening && $opening->status ==5)
                {
                    $base_table_data = Fixed_asset_opening_balance::where('company_id',$user->company_id)->firstWhere('references',$reference);
                    $opening_materials_obj = Fixed_asset_opening_with_spec::where('opening_asset_id',$base_table_data->id)->where('references',$base_table_data->references)->where('company_id',$user->company_id);
                    $omt1 = $opening_materials_obj;
                    $op_materials_with_spec = $opening_materials_obj->first();
                    if ($omt1->where('asset_id',$materials_id)->where('spec_id',$specification)->count() > 0)
                    {
                        $attr = 'duplicate';
                        $message = 'Duplicate data found!';
                    }
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
                    $attr = 'create_new';
                    $message = 'Request processed successfully.';
                }
                else if ($opening && $opening->status !=5)
                {
                    $attr = 'previously_done';
                    $message = 'Previously created!';
                }
                $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('branch_id',$project_id)->where('references',$reference)->first();
                $view = view('back-end.asset._fixed_asset_opening_body_list',compact('final_opening'))->render();
                return \response()->json([
                    'status'=>'success',
                    'attribute'=>$attr,
                    'data'=>$view,
                    'message'=>$message
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
    public function getFixedAssetOpening(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'ref'=>['sometimes','nullable','string',],
                'project'=>['sometimes','nullable','string','exists:branches,id'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $reference = $ref;
                if (empty($ref))
                {
                    return \response()->json([
                        'status'=>'error',
                        'message'=>'Reference is required!'
                    ],500);
                }
                $fa_opening = Fixed_asset_opening_balance::with(['withSpecifications'])->where('references',$reference)->where('company_id',$user->company_id)->where('branch_id',$project)->first();
                $projects = branch::where('company_id',$user->company_id)->where('status',1)->get();
                $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status',1)->get();
                if ($fa_opening && $fa_opening->status == 5)//processing
                {
                    $final_opening = Fixed_asset_opening_balance::with(['withSpecifications','withSpecifications.asset','withSpecifications.specification','branch'])->where('company_id',$user->company_id)->where('branch_id',$project)->where('references',$reference)->first();
                    $view = view('back-end.asset._fixed_asset_opening_body',compact('final_opening','fa_opening','reference','projects','fixed_assets'))->render();
                }
                else{
                    if ($ref_via_search = Fixed_asset_opening_balance::where('references',$reference)->where('company_id',$user->company_id)->first())
                    {
                        return \response()->json([
                            'status'=>'warning',
                            'message'=>'This reference already exists!'
                        ],200);
                    }
                    $view = view('back-end.asset._fixed_asset_opening_body',compact('fa_opening','reference','projects','fixed_assets'))->render();
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


}
