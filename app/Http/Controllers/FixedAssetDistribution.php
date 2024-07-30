<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Fixed_asset;
use App\Models\Fixed_asset_opening_balance;
use App\Models\fixed_asset_specifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Response;
use Throwable;

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
                'opdate'    => ['required','date'],
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
    public function getFixedAssetOpening(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'ref'=>['required','string',],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $reference = $ref;
                $fa_opening = Fixed_asset_opening_balance::with(['withSpecifications'])->where('references',$reference)->where('company_id',$user->company_id)->first();
                $projects = branch::where('company_id',$user->company_id)->where('status',1)->get();
                $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status',1)->get();
                if ($fa_opening && $fa_opening->status == 5)//processing
                {

                }
                else{
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
