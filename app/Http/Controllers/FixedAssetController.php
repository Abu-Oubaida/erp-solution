<?php

namespace App\Http\Controllers;

use App\Models\Fixed_asset;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FixedAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|RedirectResponse|Response|View
     */
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('POST')) {
                return $this->store($request);
            }
            else{
                $user = Auth::user();
                $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->get();
                return view('back-end.asset.fixed-asset-add',compact('fixed_assets'));
            }
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'recourse_code' => ['string','required','unique:fixed_assets,recourse_code'],
            'materials' => ['string','required','unique:fixed_assets,materials_name'],
            'rate'  =>  ['numeric','required'],
            'unit'  =>  ['string','required'],
            'status'  =>  ['numeric','required','between:0,1'],
            'depreciation'  =>  ['numeric','sometimes','nullable'],
            'remarks'   =>  ['string','nullable'],
        ]);
        try {
            $user = Auth::user();
            extract($request->post());
            Fixed_asset::create([
                'recourse_code' =>  $recourse_code,
                'materials_name'    =>  $materials,
                'rate'  =>  $rate,
                'unit'  =>  $unit,
                'depreciation'  => $depreciation,
                'status'    =>  $status,
                'remarks'   =>  $remarks,
                'company_id'=> $user->company_id,
                'created_by'=>  $user->id,
            ]);
            return back()->with('success','Fixed Asset Added Successfully');
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return Application|Factory|View|RedirectResponse
     */
    public function show(Fixed_asset $fixed_asset)
    {
        try {
            $user = Auth::user();
            $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->get();
            return view('back-end.asset.fixed-asset-list',compact('fixed_assets'));
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return Response
     */
    public function edit(Fixed_asset $fixed_asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return Response
     */
    public function update(Request $request, Fixed_asset $fixed_asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return Response
     */
    public function destroy(Fixed_asset $fixed_asset)
    {
        //
    }
}
