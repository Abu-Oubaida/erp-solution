<?php

namespace App\Http\Controllers;

use App\Http\Controllers\superadmin\ajaxRequestController;
use App\Models\Fixed_asset;
use App\Models\Fixed_asset_delete_history;
use App\Models\fixed_asset_specifications;
use App\Models\User;
use App\Traits\ParentTraitCompanyWise;
use Database\Seeders\CompanyInfo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Throwable;
use function PHPUnit\Framework\isFalse;

class FixedAssetController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Redirector|RedirectResponse
     */
    public function index()
    {
        return redirect(route('fixed.asset.add'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|RedirectResponse|Response|View
     */
    public function create(Request $request)
    {
        try {
            $permission = $this->permissions()->add_fixed_asset;
            if ($request->isMethod('POST')) {
                return $this->store($request);
            }
            else{
                $user = Auth::user();
                $fixed_assets = $this->getFixedAssets($permission)->where('created_by',$user->id)->where('status','<=',2)->get();
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
//                dd($companies);
                return view('back-end.asset.fixed-asset-add',compact('fixed_assets','companies'));
            }
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function createSpecification(Request $request)
    {
        try {
            $permission = $this->permissions()->add_fixed_asset_specification;
            if ($request->isMethod('POST')) {
                if ($request->input('search') == 'search')
                {
                    $request->validate([
                        'recourse_code' => ['string','required','exists:fixed_assets,recourse_code'],
                        'company' => ['string','required','exists:company_infos,id'],
                    ]);
                    extract($request->post());
                    $fa = $this->getFixedAssets($permission)->where('recourse_code',$recourse_code)->first();
                    return redirect(route('fixed.asset.specification',['c'=>$company,'fid'=>$fa->id,'code'=>$fa->recourse_code,'name'=>$fa->materials_name]))->withInput();
                }
                return back()->with('error','Invalid Request of Post Methode!');
            }
            else{
                $fixed_asset_specifications = $this->getFixedAssetSpecification($permission);
                $fixed_assets= null;
                if ($request->get('fid') && $request->get('c'))
                {
                    $fid = $request->get('fid');
                    $company_id = $request->get('c');
                    $fixed_asset = $this->getFixedAssets($permission)->where('company_id',$company_id)->where('status',1)->where('id',$fid)->first();
                    $fixed_asset_specifications = $fixed_asset_specifications->where('fixed_asset_id',$fixed_asset->id);
                    $fixed_assets= $this->getFixedAssets($permission)->where('company_id',$company_id)->get();
                }
                $fixed_asset_specifications = $fixed_asset_specifications->orderBy('created_at','DESC')->get();
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                return view('back-end.asset.fixed-asset-specification-add',compact('fixed_assets','fixed_asset_specifications','companies'));
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
    private function store(Request $request)
    {
        $request->validate([
            'company'=> ['required', 'integer', 'exists:company_infos,id'],
            'recourse_code' => ['string','required',Rule::unique('fixed_assets','recourse_code')->where(function ($query) use ($request){
                return $query->where('company_id',$request->post('company'));
            })->ignore(3,'status')],
            'materials' => ['string','required',Rule::unique('fixed_assets','materials_name')->where(function ($query) use ($request){
                return $query->where('company_id',$request->post('company'));
            })->ignore(3,'status')],
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
                'company_id'=> $company,
                'created_by'=>  $user->id,
            ]);
            return back()->with('success','Fixed Asset Added Successfully');
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function specificationStore(Request $request)
    {
        try {
            $permission = $this->permissions()->add_fixed_asset_specification;
            $request->validate([
                'cid' =>  ['required','string','exists:company_infos,id'],
                'fid' =>  ['required','string','exists:fixed_assets,id'],
                'specification' =>  ['required','string',Rule::unique('fixed_asset_specifications','specification')->where('fixed_asset_id',$request->input('fid'))->where('company_id',$request->input('cid'))],
                'status'  =>  ['required','numeric','between:0,1'],
            ]);
            if ($request->isMethod('POST')) {
                extract($request->post());
                $fixed_asset = $this->getFixedAssets($permission)->where('status',1)->where('id',$fid)->first();
                if ($fixed_asset)
                {
                    fixed_asset_specifications::create([
                        'fixed_asset_id'=>  $fixed_asset->id,
                        'status'        =>  $status,
                        'specification' =>  $specification,
                        'company_id'    =>  $cid,
                        'created_by'    =>  $this->user->id,
                        'created_at'    =>  now()
                    ]);
                    $fixed_asset_specifications = $this->getFixedAssetSpecification($permission)->where('company_id',$cid)->where('fixed_asset_id',$fixed_asset->id)->get();
                    $view = view('back-end.asset._fixed-asset-specification-list',compact('fixed_asset_specifications'))->render();
                    return response()->json([
                        'status' => 'success',
                        'message'=>'Request processed Successfully',
                        'data' => $view,
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'message'=>'Materials Not Found!',
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=>'Request Method Not Allowed!',
            ]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => 'error',
                'message'=>$exception->getMessage(),
            ]);
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
            $permission = $this->permissions()->add_fixed_asset;
            $fixed_assets = $this->getFixedAssets($permission)->where('status','<=',2)->get();
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
            $permission = $this->permissions()->fixed_asset_edit;
            $id = Crypt::decryptString($fixedAssetID);
            if ($request->isMethod('put')) {
                return $this->update($request, $id);
            }
            $fixed_assets = $this->getFixedAssets($permission)->where('status','<=',2)->get();
            $fixed_asset = $this->getFixedAssets($permission)->where('status','<=',2)->where('id',$id)->first();
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.asset.fixed-asset-edit',compact('fixed_assets','fixed_asset','companies'));
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    public function editSpecification(Request $request, $fasid)
    {
        try {
            $user = Auth::user();
            $id = Crypt::decryptString($fasid);
            if ($request->isMethod('PUT')) {
                $this->updateSpecefication($request, $id, $user);
            }
            $fas = fixed_asset_specifications::with('fixed_asset')->where('id',$id)->where('company_id',$user->company_id)->first();
            $fixed_asset_specifications = fixed_asset_specifications::with(['fixed_asset','createdBy','createdBy'])->where('company_id',$user->company_id)->get();
            if ($fas)
                return view('back-end.asset.fixed-asset-specification-edit',compact('fas','fixed_asset_specifications'));
            else
                return back()->with('error','Data Not Found!')->withInput();
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    private function update(Request $request,$fixedAssetID)
    {
        try {
            $permission = $this->permissions()->fixed_asset_edit;
            $request->validate([
                'company' =>  ['required', 'integer','exists:company_infos,id'],
                'recourse_code' => ['string','required', Rule::unique('fixed_assets','recourse_code')->ignore($fixedAssetID,'id')],
                'materials' => ['string','required',Rule::unique('fixed_assets','materials_name')->ignore($fixedAssetID,'id')],
                'rate'  =>  ['numeric','required'],
                'unit'  =>  ['string','required'],
                'status'  =>  ['numeric','required','between:0,1'],
                'depreciation'  =>  ['string','sometimes','nullable'],
                'remarks'   =>  ['string','nullable'],
            ]);
            extract($request->post());
            $this->getFixedAssets($permission)->where('id',$fixedAssetID)->update([
                'company_id' =>  $company,
                'recourse_code' =>  $recourse_code,
                'materials_name'    =>  $materials,
                'rate'  =>  $rate,
                'unit'  =>  $unit,
                'depreciation'  => $depreciation,
                'status'    =>  $status,
                'remarks'   =>  $remarks,
                'updated_by'=>  $this->user->id,
                'updated_at'=>  now(),
            ]);
            return back()->with('success','Fixed Asset Updated Successfully');
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function updateSpecefication(Request $request,$fasid, $user)
    {
        $request->validate([
            'fixed_asset_id' =>  ['required','string','exists:fixed_assets,id'],
            'specification'  =>  ['required','string',Rule::unique('fixed_asset_specifications','specification')->where('fixed_asset_id',$request->input('fixed_asset_id'))->whereNot('id',$fasid)],
            'status'         =>  ['required','numeric','between:0,1'],
        ]);
        try {
            fixed_asset_specifications::where('id',$fasid)->update([
                'specification' =>  $request->input('specification'),
                'status'        =>  $request->input('status'),
                'updated_by'    =>  $user->id,
                'updated_at'    =>  now(),
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
            $permission = $this->permissions()->fixed_asset_edit;
            $request->validate([
                'id'    =>  ['string','required'],
            ]);
            extract($request->post());
            $deleteID = Crypt::decryptString($id);
            $fixed_asset_delete = $this->getFixedAssets($permission)->where('id',$deleteID);
            if (count($fixed_asset_delete->first()->witRefUses) || count($fixed_asset_delete->first()->specifications))
            {
                return back()->with('warning','Data delete not possible, a relationship exists with another table.');
            }
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
                    'created_by'=>  $this->user->id,
                ]);
            }
            $fixed_asset_delete->delete();
            return redirect(route('fixed.asset.show'))->with('success','Data deleted successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroySpecification(Request $request)
    {
        try {
            $permission = $this->permissions()->delete_fixed_asset_specification;
            $request->validate([
                'id'    =>  ['string','required'],
            ]);
            extract($request->post());
            $deleteID = Crypt::decryptString($id);
            $data = $this->getFixedAssetSpecification($permission)->where('id',$deleteID);
            if (count($data->first()->fixedWithRefData))
            {
                return back()->with('warning','Data delete not possible, a relationship exists with another table.');
            }
            if ($f = $data->first())
            {
                DB::table('fixed_asset_specification_delete_histories')->insert([
                    'old_id' => $f->id,
                    'fixed_asset_id' => $f->fixed_asset_id,
                    'status' =>  $f->status,
                    'specification' =>  $f->specification,
                    'company_id' =>  $f->company_id,
                    'created_by' =>  $f->created_by,
                    'updated_by' =>  $f->updated_by,
                    'deleted_by' => $this->user->id,
                    'old_created_at' => $f->created_at,
                    'old_updated_at' => $f->updated_at,
                    'created_at' => now(),
                ]);
            }
            $data->delete();
            return redirect(route('fixed.asset.specification'))->with('success','Data deleted successfully.');
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function companyWiseFixedAsset(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_interface;
            if ($request->isMethod('POST')) {
                $request->validate([
                   'id' => ['required', 'string', 'exists:company_infos,id'],
                ]);
                extract($request->post());
                $fx_assets = $this->getFixedAssets($permission)->where('status',1)->where('company_id',$id)->get();
                return response()->json([
                    'status'    =>  'success',
                    'data'      =>  $fx_assets,
                    'message'   =>  'Request processed successfully.',
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=> "Request method not allowed!",
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function projectsWiseFixedAssets(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_interface;
            if ($request->isMethod('POST')) {
                $validData = $request->validate([
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                    'project_ids' => ['required', 'array'],
                    'project_ids.*' => ['required', 'string',],
                ]);
                extract($validData);
                if (in_array(0,$project_ids))
                {
                    $project_ids = $this->getUserProjectPermissions($this->user->id,$project_ids)->where('company_id',$company_id)->get()->unique()->pluck('id')->toArray();
                }
                $materials_id = $this->getFixedAssetStockMaterials($permission,$project_ids,$company_id);
                $materials = $this->getFixedAssets($permission)->where('status',1)->whereIn('id',$materials_id)->get();
                return response()->json([
                    'status'    =>  'success',
                    'data'      =>  ['data' => $materials],
                    'message'   =>  'Request processed successfully.',
                ]);
            }
            return response()->json([
                'status'    =>  'error',
                'message'=> "Request method not allowed!",
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function stockReport(Request $request)
    {
        try {
            $permission = $this->permissions()->fixed_asset_report;
            $companies = $this->getCompany($permission)->get();
            $view = view('back-end.asset.report.stock.index',compact('companies'))->render();
            return $view;
        }catch (\Throwable $exception)
        {
            return back()->with('error', $exception->getMessage());
        }
    }
}
