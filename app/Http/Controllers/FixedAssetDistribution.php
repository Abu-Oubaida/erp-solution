<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Fixed_asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
