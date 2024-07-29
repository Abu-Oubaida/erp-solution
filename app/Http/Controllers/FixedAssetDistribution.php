<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Fixed_asset;
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
            $projects = branch::where('company_id',$user->company_id)->where('status',1)->get();
            $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status',1)->get();
            return view('back-end.asset.fixed-asset-opening',compact('projects','fixed_assets'));
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
    public function getFixedAsset(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'id'=>['required','string','exists:fixed_asset_specifications,id'],
            ]);
            if ($request->isMethod('post'))
            {
                extract($request->post());
                $spec = fixed_asset_specifications::with(['fixed_asset'])->where('id',$id)->where('status',1)->where('company_id',$user->company_id)->first();
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
}
