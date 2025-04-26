<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\department;
use App\Models\SalesLeadApartmentSize;
use App\Models\SalesLeadBudget;
use App\Models\SalesLeadFacing;
use App\Models\SalesLeadFloor;
use App\Models\SalesLeadLocationInfo;
use App\Models\SalesLeadSourceInfo;
use App\Models\SalesLeadStatusInfo;
use App\Models\SalesLeadView;
use App\Models\SalesProfession;
use Illuminate\Http\Request;
use App\Traits\ParentTraitCompanyWise;
use Log;
use App\Models\SalesLeadApartmentType;
use Illuminate\Validation\ValidationException;

class SalesInterfaceController extends Controller
{
    //
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
        return view('back-end.sales.dashboard');
    }
    public function addLead(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->storeLead($request);
            }
            $depts = department::where('status',1)->get();
            $branches = branch::where('status',1)->get();
//            dd($depts);
            $leadWiseLocations = [
                [ 'id' => 1, 'dept_name' => 'Dhaka' ],
                [ 'id' => 2, 'dept_name' => 'Narayanganj' ],
                [ 'id' => 3, 'dept_name' => 'Rupganj' ],
                [ 'id' => 4, 'dept_name' => 'Dhanmondi' ],
            ];
            return view('back-end/sales/add-lead',compact('depts','branches','leadWiseLocations'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function storeLead(Request $request)
    {
        try {
            return true;
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function leadList()
    {
        try {
            return true;
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function saleSettingsInterface(){
        try {
            $permission = $this->permissions()->sale_settings;
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            $company_ids = $this->getCompanyModulePermissionWise($permission)->get()->pluck('id')->toArray();
            $salesLeadApartmentType=SalesLeadApartmentType::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadApartmentSize = SalesLeadApartmentSize::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadView = SalesLeadView::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadBudget = SalesLeadBudget::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadSourceInfo = SalesLeadSourceInfo::latest()->whereIn('company_id',$company_ids)->get();
            $salesProfession = SalesProfession::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadLocationInfo = SalesLeadLocationInfo::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadFloor = SalesLeadFloor::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadStatusInfo = SalesLeadStatusInfo::latest()->whereIn('company_id',$company_ids)->get();
            $salesLeadFacing = SalesLeadFacing::latest()->whereIn('company_id',$company_ids)->get();
            
            return view('back-end/sales/sell-setting',compact('companies','salesLeadApartmentType','salesLeadApartmentSize','salesLeadView','salesLeadBudget','salesLeadSourceInfo','salesProfession','salesLeadLocationInfo','salesLeadFloor','salesLeadStatusInfo','salesLeadFacing'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function saleSubTableDataAdd(Request $request){
        try {
        $subTableData = (object) $request->subTableData;
        $commonFields = [
            'company_id' => $subTableData->company,
            'status'     => $subTableData->status ?? null,
            'created_by' => auth()->user()->id,
            'updated_by' => null,
        ];

        $models = [
            'sales_lead_apartment_type' => [SalesLeadApartmentType::class, ['title'],''],
            'sales_lead_apartment_size' => [SalesLeadApartmentSize::class, ['title', 'size']],
            'sales_lead_view'           => [SalesLeadView::class, ['title']],
            'sales_lead_budget'         => [SalesLeadBudget::class, ['title']],
            'sales_lead_source_info'    => [SalesLeadSourceInfo::class, ['title', 'parent_id', 'is_parent']],
            'sales_lead_profession'     => [SalesProfession::class, ['title', 'parent_id', 'is_parent']],
            'sales_lead_location_info'  => [SalesLeadLocationInfo::class, ['location_name']],
            'sales_lead_floor'          => [SalesLeadFloor::class, ['title']],
            'sales_lead_status_info'    => [SalesLeadStatusInfo::class, ['title']],
            'sales_lead_facing'         => [SalesLeadFacing::class, ['title']],
        ];

        $validationRules = [
            'subTableData.company' => 'required',
        ];

        // Duplicate title check for specific company
        $param = $subTableData->hidden_click_param;
        if (in_array('title', $models[$param][1])) {
            $currentClass = $models[$param][0];
            $companyId = $subTableData->company;
            $title = $subTableData->title ?? '';
        
            $isTitleExist = $currentClass::where('company_id', $companyId)
                                ->where('title', $title)
                                ->exists();
        
            if ($isTitleExist) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This title already exists for the selected company.',
                ]);
            }
        
            // Still require the title
            $validationRules['subTableData.title'] = 'required';
        }
        
        // Now run the validation
        $request->validate($validationRules);
        $show_output = 'output_'.$param;

        if (!isset($models[$param])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data type.'
            ]);
        }

        [$modelClass, $fields] = $models[$param];
        $dataToInsert = $commonFields;

        foreach ($fields as $field) {
            $dataToInsert[$field] = $subTableData->$field ?? null;
        }

        $record = $modelClass::create($dataToInsert);
        $viewPath = 'back-end.sales.'.str_replace('_','-',$param).'-list';
        $getSaleSubTableData = $this->getSaleSubTableData($modelClass);
        $view = view($viewPath, compact('getSaleSubTableData'))->render();
        if ($record) {
            return response()->json([
                'status' => 'success',
                'message' => 'Added Successfully',
                'data'=>$view,
                'output_id'=>$show_output
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation failed'
            ]);
        }
    } catch (\Throwable $exception){
        return back()->with('error',$exception->getMessage())->withInput();
    }
 }
 public function getSaleSubTableData($modelClass)
{
    $permission = $this->permissions()->sale_settings;
    $query = $modelClass::query();
    $company_ids = $this->getCompanyModulePermissionWise($permission)->get()->pluck('id')->toArray();
    if ($company_ids) {
        $query->whereIn('company_id', $company_ids);
    }

    return $query->latest()->get();
}
public function getSaleProfessionTitleId(Request $request){
   try{
    $selectedId=$request->selectedId;
    $salesProfessionData = SalesProfession::where('company_id',$selectedId)->get();
    Log::info(json_encode($salesProfessionData,JSON_PRETTY_PRINT));
    if (!$salesProfessionData->isEmpty() ) {
         return response()->json([
             'status' => 'success',
             'salesProfessionData'=>$salesProfessionData
         ]);
     } else {
         return response()->json([
             'status' => 'error',
             'message' => 'Operation failed'
         ]);
     }
   }catch (\Throwable $exception){
        return back()->with('error',$exception->getMessage())->withInput();
    }
}
}
