<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\DeleteSalesApartmentSizeMultipleHistory;
use App\Models\DeleteSalesLeadBudgetMultipleHistory;
use App\Models\DeleteSalesLeadFacingMultipleHistory;
use App\Models\DeleteSalesLeadFloorMultipleHistory;
use App\Models\DeleteSalesLeadLocationMultipleHistory;
use App\Models\DeleteSalesLeadProfessionMultipleHistory;
use App\Models\DeleteSalesLeadSourceMultipleHistory;
use App\Models\DeleteSalesLeadStatusMultipleHistory;
use App\Models\DeleteSalesLeadViewMultipleHistory;
use App\Models\DeleteTypeMultipleHistory;
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
use Exception;
use Illuminate\Http\Request;
use App\Traits\ParentTraitCompanyWise;
use Log;
use App\Models\SalesLeadApartmentType;
use Illuminate\Validation\ValidationException;
use Auth;
use DB;

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
            if ($request->isMethod('post')) {
                return $this->storeLead($request);
            }
            $depts = department::where('status', 1)->get();
            $branches = branch::where('status', 1)->get();
            //            dd($depts);
            $leadWiseLocations = [
                ['id' => 1, 'dept_name' => 'Dhaka'],
                ['id' => 2, 'dept_name' => 'Narayanganj'],
                ['id' => 3, 'dept_name' => 'Rupganj'],
                ['id' => 4, 'dept_name' => 'Dhanmondi'],
            ];
            return view('back-end/sales/add-lead', compact('depts', 'branches', 'leadWiseLocations'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    private function storeLead(Request $request)
    {
        try {
            return true;
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function leadList()
    {
        try {
            return true;
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }
    public function saleSettingsInterface()
    {
        try {
            $permission = $this->permissions()->sale_settings;
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            $company_ids = $this->getCompanyModulePermissionWise($permission)->get()->pluck('id')->toArray();
            $salesLeadApartmentType = SalesLeadApartmentType::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadApartmentSize = SalesLeadApartmentSize::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadView = SalesLeadView::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadBudget = SalesLeadBudget::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadSourceInfo = SalesLeadSourceInfo::with(['parentTitle', 'createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesProfession = SalesProfession::with(['parentTitle', 'createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadLocationInfo = SalesLeadLocationInfo::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadFloor = SalesLeadFloor::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadStatusInfo = SalesLeadStatusInfo::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();
            $salesLeadFacing = SalesLeadFacing::with(['createdByUser', 'getCompanyName'])->latest()->whereIn('company_id', $company_ids)->get();

            return view('back-end/sales/sell-setting', compact('companies', 'salesLeadApartmentType', 'salesLeadApartmentSize', 'salesLeadView', 'salesLeadBudget', 'salesLeadSourceInfo', 'salesProfession', 'salesLeadLocationInfo', 'salesLeadFloor', 'salesLeadStatusInfo', 'salesLeadFacing'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function saleSubTableDataAdd(Request $request)
    {
        try {
            $subTableData = (object) $request->subTableData;
            $commonFields = [
                'company_id' => $subTableData->company,
                'status' => $subTableData->status ?? null,
                'created_by' => auth()->user()->id,
                'updated_by' => null,
            ];

            $models = [
                'sales_lead_apartment_type' => [SalesLeadApartmentType::class, ['title'], ''],
                'sales_lead_apartment_size' => [SalesLeadApartmentSize::class, ['title', 'size']],
                'sales_lead_view' => [SalesLeadView::class, ['title']],
                'sales_lead_budget' => [SalesLeadBudget::class, ['title']],
                'sales_lead_source_info' => [SalesLeadSourceInfo::class, ['title', 'parent_id', 'is_parent']],
                'sales_lead_profession' => [SalesProfession::class, ['title', 'parent_id', 'is_parent']],
                'sales_lead_location_info' => [SalesLeadLocationInfo::class, ['location_name']],
                'sales_lead_floor' => [SalesLeadFloor::class, ['title']],
                'sales_lead_status_info' => [SalesLeadStatusInfo::class, ['title']],
                'sales_lead_facing' => [SalesLeadFacing::class, ['title']],
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
            $show_output = 'output_' . $param;

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
            if (isset($dataToInsert['parent_id']) && isset($dataToInsert['is_parent'])) {
                if ($dataToInsert['parent_id'] == 1 && $dataToInsert['is_parent'] == 1) {
                    $dataToInsert['parent_id'] = null;
                }
            }

            $record = $modelClass::create($dataToInsert);
            $viewPath = 'back-end.sales.' . str_replace('_', '-', $param) . '-list';
            $getSaleSubTableData = $this->getSaleSubTableData($modelClass);
            $view = view($viewPath, compact('getSaleSubTableData'))->render();
            if ($record) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Added Successfully',
                    'data' => $view,
                    'output_id' => $show_output
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Operation failed'
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation failed' . $exception->getMessage()
            ]);
        }
    }
    public function getSaleSubTableData($modelClass)
    {
        $permission = $this->permissions()->sale_settings;
        $query = $modelClass::query();
        $company_ids = $this->getCompanyModulePermissionWise($permission)->get()->pluck('id')->toArray();
        if ($company_ids) {
            $query->whereIn('company_id', $company_ids)->with('createdByUser');
        }
        $modelInstance = new $modelClass;
        $fillableFields = $modelInstance->getFillable();

        if (in_array('parent_id', $fillableFields) && in_array('is_parent', $fillableFields)) {
            $query->with('parentTitle');
        }
        return $query->latest()->get();
    }
    public function getSaleProfessionTitleId(Request $request)
    {
        try {
            $selectedId = $request->selectedId;
            // Log::info(json_encode($selectedId,JSON_PRETTY_PRINT));
            // return;
            $salesProfessionData = SalesProfession::query()->where(function ($query) use ($selectedId) {
                $query->where('company_id', $selectedId)
                    ->where('status', 1)
                    ->where('is_parent',1);
            })->orWhere('company_id', 0)->get();
            if (!$salesProfessionData->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'salesProfessionData' => $salesProfessionData
                ]);
            } else {
                throw new Exception('Data Not Found');
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation failed' . $exception->getMessage()
            ]);
        }
    }
    public function getSaleSourceTitleId(Request $request)
    {
        try {
            $selectedId = $request->selectedId;
            $salesSourceData = SalesLeadSourceInfo::query()->where(function ($query) use ($selectedId) {
                $query->where('company_id', $selectedId)
                    ->where('status', 1)
                    ->where('is_parent',1);
            })->orWhere('company_id', 0)->get();
            if (!$salesSourceData->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'salesSourceData' => $salesSourceData
                ]);
            } else {
                throw new Exception('Data Not Found');
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation failed' . $exception->getMessage()
            ]);
        }
    }
    public function getSalesLeadApartmentTypeEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadApartmentType::class, $param_for_edit, 'back-end.sales.sales-lead-apartment-type-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadApartmentType::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }


    public function setDataOfApartment($modal, $param_for_edit, $blade, $filteredSubTableData, $show_output)
    {
        try {
            $salesLeadModalDataFind = $modal::find($param_for_edit);
            if ($salesLeadModalDataFind) {
                $updated = $salesLeadModalDataFind->update($filteredSubTableData);
                $getSaleSubTableData = $this->getDataOfApartment($modal);
                $view = view($blade, compact('getSaleSubTableData'))->render();
                if ($updated) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data Updated Successfully',
                        'output_id' => $show_output,
                        'data' => $view,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Data Updation Fail'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record Not Found'
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Updation Fail ' . $exception->getMessage()
            ]);
        }
    }

    public function getDataOfApartment($modal)
    {
        return $modal::latest()->get();
    }

    public function getSalesLeadApartmentSizeEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadApartmentSize::class, $param_for_edit, 'back-end.sales.sales-lead-apartment-size-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadApartmentSize::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadSourceInfoEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadSourceInfo::class, $param_for_edit, 'back-end.sales.sales-lead-source-info-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadSourceInfo::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadBudgetEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadBudget::class, $param_for_edit, 'back-end.sales.sales-lead-budget-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadBudget::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadLocationInfoEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadLocationInfo::class, $param_for_edit, 'back-end.sales.sales-lead-location-info-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadLocationInfo::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadViewEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadView::class, $param_for_edit, 'back-end.sales.sales-lead-view-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadView::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadFloorEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadFloor::class, $param_for_edit, 'back-end.sales.sales-lead-floor-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadFloor::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadProfessionEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesProfession::class, $param_for_edit, 'back-end.sales.sales-lead-profession-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesProfession::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadFacingEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadFacing::class, $param_for_edit, 'back-end.sales.sales-lead-facing-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadFacing::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function getSalesLeadStatusInfoEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $subTableData = (object) $request->subTableData;
            $filteredSubTableData = collect($request->subTableData)->except([
                'hidden_click_param',
                'hidden_input_for_output'
            ])->toArray();
            $param = $subTableData->hidden_input_for_output;
            $show_output = 'output_' . $param;
            $param_for_edit = $subTableData->hidden_click_param;
            $result = $this->setDataOfApartment(SalesLeadStatusInfo::class, $param_for_edit, 'back-end.sales.sales-lead-status-info-list', $filteredSubTableData, $show_output);

            return response()->json($result);
        }
        $data = SalesLeadStatusInfo::find($request->record_id);
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    public function deleteTypeMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadApartmentType::class, DeleteTypeMultipleHistory::class, $ids,'back-end.sales.sales-lead-apartment-type-list','output_sales_lead_apartment_type');
        return response()->json($result);
    }
    public function deleteSalesLeadApartmentSizeMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadApartmentSize::class, DeleteSalesApartmentSizeMultipleHistory::class, $ids,'back-end.sales.sales-lead-apartment-size-list','output_sales_lead_apartment_size');
        return response()->json($result);
    }
    public function deleteSalesLeadViewMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadView::class, DeleteSalesLeadViewMultipleHistory::class, $ids,'back-end.sales.sales-lead-view-list','output_sales_lead_view');
        return response()->json($result);
    }
    public function deleteSalesLeadBudgetMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadBudget::class, DeleteSalesLeadBudgetMultipleHistory::class, $ids,'back-end.sales.sales-lead-budget-list','output_sales_lead_budget');
        return response()->json($result);
    }
    public function deleteSalesLeadSourceMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadSourceInfo::class, DeleteSalesLeadSourceMultipleHistory::class, $ids,'back-end.sales.sales-lead-source-info-list','output_sales_lead_source_info');
        return response()->json($result);
    }
    public function deleteSalesLeadProfessionMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesProfession::class, DeleteSalesLeadProfessionMultipleHistory::class, $ids,'back-end.sales.sales-lead-profession-list','output_sales_lead_profession');
        return response()->json($result);
    }
    public function deleteSalesLeadLocationMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadLocationInfo::class, DeleteSalesLeadLocationMultipleHistory::class, $ids,'back-end.sales.sales-lead-location-info-list','output_sales_lead_location_info');
        return response()->json($result);
    }
    public function deleteSalesLeadFloorMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadFloor::class, DeleteSalesLeadFloorMultipleHistory::class, $ids,'back-end.sales.sales-lead-floor-list','output_sales_lead_floor');
        return response()->json($result);
    }
    public function deleteSalesLeadStatusMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadStatusInfo::class, DeleteSalesLeadStatusMultipleHistory::class, $ids,'back-end.sales.sales-lead-status-info-list','output_sales_lead_status_info');
        return response()->json($result);
    }
    public function deleteSalesLeadFacingMultiple(Request $request)
    {
        $ids = $request->selected;
        $result=$this->deleteMultiple(SalesLeadFacing::class, DeleteSalesLeadFacingMultipleHistory::class, $ids,'back-end.sales.sales-lead-facing-list','output_sales_lead_facing');
        return response()->json($result);
    }
    private function deleteMultiple($modal, $modalHistory, $ids,$blade,$output)
    {
        try {
            DB::beginTransaction();
            $modalHistoryData = $modal::whereIn('id', $ids)->get();
            // Log::info(json_encode($modalHistoryData,JSON_PRETTY_PRINT));
            $historyProcess = $modalHistoryData->map(function ($item) {
                $commonData = [
                    'old_id' => $item->id,
                    'old_created_by' => $item->created_by,
                    'old_updated_by' => $item->updated_by,
                    'old_created_at' => $item->created_at,
                    'old_updated_at' => $item->updated_at,
                    'created_by' => Auth::id(),
                    'updated_by' => null,
                    'company_id' => $item->company_id,
                    'status' => $item->status,
                ];
                if (!empty($item->title)) {
                    $commonData['title'] = $item->title;
                }
                if (!empty($item->size)) {
                    $commonData['size'] = $item->size;
                }
                if (!empty($item->location_name)) {
                    $commonData['location_name'] = $item->location_name;
                }
                if (!empty($item->is_parent)) {
                    $commonData['is_parent'] = $item->is_parent;
                }
                if (property_exists($item,'parent_id')) {
                    $commonData['parent_id'] = $item->parent_id ?? null;
                }
                
                if (isset($item->is_parent) && $item->is_parent==0) {
                    $commonData['is_parent'] = 0;
                }
                return $commonData;
            })->toArray();
            $historyCreate = $modalHistory::insert($historyProcess);
            $multipleDelete = $modal::whereIn('id', $ids)->delete();
            if($historyCreate && $multipleDelete ){
                $getSaleSubTableData = $this->getDataOfApartment($modal);
                $view = view($blade, compact('getSaleSubTableData'))->render();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Deleted Successfully',
                    'data'=>$view,
                    'output'=>$output
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Delation Fail',
                ]);
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Error Occured ' . $exception->getMessage()
            ]);
        }
    }

}
