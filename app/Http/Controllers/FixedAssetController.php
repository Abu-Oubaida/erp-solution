<?php

namespace App\Http\Controllers;

use App\Models\Fixed_asset;
use App\Models\Fixed_asset_delete_history;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

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
                $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('created_by',$user->id)->where('status','<=',2)->get();
                return view('back-end.asset.fixed-asset-add',compact('fixed_assets'));
            }
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
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
            'recourse_code' => ['string','required',Rule::unique('fixed_assets','recourse_code')->ignore(3,'status')],
            'materials' => ['string','required',Rule::unique('fixed_assets','materials_name')->ignore(3,'status')],
            'rate'  =>  ['numeric','required'],
            'unit'  =>  ['string','required'],
            'status'  =>  ['numeric','required','between:0,1'],
            'depreciation'  =>  ['string','sometimes','nullable'],
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
            $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status','<=',2)->get();
            return view('back-end.asset.fixed-asset-list',compact('fixed_assets'));
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View|RedirectResponse|Response
     */
    public function edit(Request $request, $fixedAssetID)
    {
        try {
            $user = Auth::user();
            $id = Crypt::decryptString($fixedAssetID);
            if ($request->isMethod('put')) {
                return $this->update($request, $id);
            }
            $fixed_assets = Fixed_asset::where('company_id',$user->company_id)->where('status','<=',2)->get();
            $fixed_asset = Fixed_asset::where('company_id',$user->company_id)->where('status','<=',2)->where('id',$id)->first();
            return view('back-end.asset.fixed-asset-edit',compact('fixed_assets','fixed_asset'));
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    public function update(Request $request,$fixedAssetID)
    {
        $request->validate([
            'recourse_code' => ['string','required', Rule::unique('fixed_assets','recourse_code')->ignore($fixedAssetID,'id')],
            'materials' => ['string','required',Rule::unique('fixed_assets','materials_name')->ignore($fixedAssetID,'id')],
            'rate'  =>  ['numeric','required'],
            'unit'  =>  ['string','required'],
            'status'  =>  ['numeric','required','between:0,1'],
            'depreciation'  =>  ['string','sometimes','nullable'],
            'remarks'   =>  ['string','nullable'],
        ]);
        try {
            $user = Auth::user();
            extract($request->post());
            Fixed_asset::where('company_id',$user->company_id)->where('id',$fixedAssetID)->update([
                'recourse_code' =>  $recourse_code,
                'materials_name'    =>  $materials,
                'rate'  =>  $rate,
                'unit'  =>  $unit,
                'depreciation'  => $depreciation,
                'status'    =>  $status,
                'remarks'   =>  $remarks,
                'updated_by'=>  $user->id,
                'updated_at'=>  now(),
            ]);
            return back()->with('success','Fixed Asset Updated Successfully');
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return RedirectResponse|Response
     */
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user();
            $request->validate([
                'id'    =>  ['string','required'],
            ]);
            extract($request->post());
            $deleteID = Crypt::decryptString($id);
//            $company = Fixed_asset::with(['companies'])->find($deleteID);
//            if(count($company->companies))
//            {
//                return back()->with('error','A relationship exists between other tables. Data delete not possible');
//            }
//            Fixed_asset::where('id',$deleteID)->where('company_id',$user->company_id)->update(['status'=>3]);
            $fixed_asset_delete = Fixed_asset::where('id',$deleteID)->where('company_id',$user->company_id);
            if ($f = $fixed_asset_delete->first())
            {
                Fixed_asset_delete_history::create([
                    'old_asset_id'  =>  $f->id,
                    'recourse_code' =>  $f->recourse_code,
                    'materials_name'    =>  $f->materials_name,
                    'rate'  =>  $f->rate,
                    'unit'  =>  $f->unit,
                    'depreciation'  => $f->depreciation,
                    'status'    =>  $f->status,
                    'remarks'   =>  $f->remarks,
                    'company_id'=> $f->company_id,
                    'old_created_time'  => $f->created_at,
                    'old_updated_time'  =>  $f->updated_at,
                    'old_created_by'    =>  $f->created_by,
                    'old_updated_by'    =>  $f->updated_by,
                    'created_by'=>  $user->id,
                ]);
            }
            $fixed_asset_delete->delete();
            return redirect(route('fixed.asset.show'))->with('success','Data deleted successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }
}
