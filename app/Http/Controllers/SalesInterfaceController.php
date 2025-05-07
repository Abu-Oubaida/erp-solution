<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\branch;
use App\Models\DeleteSalesApartmentSizeMultipleHistory;
use App\Models\DeleteSalesEmployeeEntryMultipleHistory;
use App\Models\DeleteSalesLeadBudgetMultipleHistory;
use App\Models\DeleteSalesLeaderEntryMultipleHistory;
use App\Models\DeleteSalesLeadFacingMultipleHistory;
use App\Models\DeleteSalesLeadFloorMultipleHistory;
use App\Models\DeleteSalesLeadLocationMultipleHistory;
use App\Models\DeleteSalesLeadProfessionMultipleHistory;
use App\Models\DeleteSalesLeadSourceMultipleHistory;
use App\Models\DeleteSalesLeadStatusMultipleHistory;
use App\Models\DeleteSalesLeadViewMultipleHistory;
use App\Models\DeleteTypeMultipleHistory;
use App\Models\department;
use App\Models\ExtraEmail;
use App\Models\ExtraMobile;
use App\Models\Lead;
use App\Models\SalePreference;
use App\Models\SalesEmployeeEntry;
use App\Models\SalesEmployeeEntryLeaderEditHistory;
use App\Models\SalesLeadApartmentSize;
use App\Models\SalesLeadBudget;
use App\Models\SalesLeaderEntry;
use App\Models\SalesLeadFacing;
use App\Models\SalesLeadFloor;
use App\Models\SalesLeadLocationInfo;
use App\Models\SalesLeadSourceInfo;
use App\Models\SalesLeadStatusInfo;
use App\Models\SalesLeadView;
use App\Models\SalesProfession;
use App\Models\Source;
use Exception;
use Illuminate\Http\Request;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
            $companies = $this->getCompany()->get();
            //dd($depts);
            $leadWiseLocations = [
                ['id' => 1, 'dept_name' => 'Dhaka'],
                ['id' => 2, 'dept_name' => 'Narayanganj'],
                ['id' => 3, 'dept_name' => 'Rupganj'],
                ['id' => 4, 'dept_name' => 'Dhanmondi'],
            ];
            return view('back-end/sales/add-lead', compact('depts', 'branches', 'leadWiseLocations', 'companies'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }
    public function addLeadStep1(Request $request)
    {
        try {
            $input = array_merge(
                $request->add_lead_step1_data,
                $request->only('alternate_mobiles_value'),
                $request->only('alternate_emails_value'),
            );

            $rules = [
                "company_id" => ['required', 'integer', 'exists:company_infos,id'],
                "full_name" => ['required', 'string', 'max:255'],
                'primary_mobile' => [
                    'required',
                    'regex:/^\+?[0-9]{9,13}$/',
                    Rule::unique('sales_leads', 'primary_mobile')->where('company_id', $request->add_lead_step1_data['company_id']),
                    Rule::unique('sales_lead_extra_mobiles', 'mobile')->where('company_id', $request->add_lead_step1_data['company_id']),
                ],
                'primary_email' => [
                    'required',
                    Rule::unique('sales_leads', 'primary_email')->where('company_id', $request->add_lead_step1_data['company_id']),
                    Rule::unique('sales_lead_extra_emails', 'email')->where('company_id', $request->add_lead_step1_data['company_id']),
                ],
                'alternate_mobiles_value' => ['array'],
                'alternate_mobiles_value.*' => [
                    'required',
                    'regex:/^\+?[0-9]{9,13}$/',
                    Rule::unique('sales_leads', 'primary_mobile')->where('company_id', $request->add_lead_step1_data['company_id']),
                    Rule::unique('sales_lead_extra_mobiles', 'mobile')->where('company_id', $request->add_lead_step1_data['company_id']),
                ],
                'alternate_emails_value' => ['array'],
                'alternate_emails_value.*' => [
                    'required',
                    Rule::unique('sales_leads', 'primary_email')->where('company_id', $request->add_lead_step1_data['company_id']),
                    Rule::unique('sales_lead_extra_emails', 'email')->where('company_id', $request->add_lead_step1_data['company_id']),
                ]
            ];
            $messages=[
                'primary_mobile.required'=>'Primary Mobile is Required',
                'primary_mobile.unique'=>'Primary Mobile already exist for this company',

                'primary_email.required'=>'Primary Email is Required',
                'primary_email.unique'=>'Primary Email already exist for this company',

                'alternate_mobiles_value.0.required'=>'Alternate Mobile 1 is required.',
                'alternate_mobiles_value.0.regex'=>'Alternate Mobile 1 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.0.unique'=>'Alternate Mobile 1 already exist for this company.',

                'alternate_mobiles_value.1.required'=>'Alternate Mobile 2 is required.',
                'alternate_mobiles_value.1.regex'=>'Alternate Mobile 2 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.1.unique'=>'Alternate Mobile 2 already exist for this company.',

                'alternate_mobiles_value.2.required'=>'Alternate Mobile 3 is required.',
                'alternate_mobiles_value.2.regex'=>'Alternate Mobile 3 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.2.unique'=>'Alternate Mobile 3 already exist for this company.',

                'alternate_mobiles_value.3.required'=>'Alternate Mobile 4 is required.',
                'alternate_mobiles_value.3.regex'=>'Alternate Mobile 4 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.3.unique'=>'Alternate Mobile 4 already exist for this company.',

                'alternate_mobiles_value.4.required'=>'Alternate Mobile 5 is required.',
                'alternate_mobiles_value.4.regex'=>'Alternate Mobile 5 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.4.unique'=>'Alternate Mobile 5 already exist for this company.',

                'alternate_emails_value.0.required'=>'Alternate Email 1 is required.',
                'alternate_emails_value.0.unique'=>'Alternate Email 1 already exist for this company.',

                'alternate_emails_value.1.required'=>'Alternate Email 2 is required.',
                'alternate_emails_value.1.unique'=>'Alternate Email 2 already exist for this company.',

                'alternate_emails_value.2.required'=>'Alternate Email 3 is required.',
                'alternate_emails_value.2.unique'=>'Alternate Email 3 already exist for this company.',

                'alternate_emails_value.3.required'=>'Alternate Email 4 is required.',
                'alternate_emails_value.3.unique'=>'Alternate Email 4 already exist for this company.',

                'alternate_emails_value.4.required'=>'Alternate Email 5 is required.',
                'alternate_emails_value.4.unique'=>'Alternate Email 5 already exist for this company.',         
            ];

            $validator = Validator::make($input, $rules,$messages);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors(),
                ]);
            }
            $validatedData = $validator->validate();
            $alternateMobiles = $validatedData['alternate_mobiles_value'] ?? '';
            $alternateEmails = $validatedData['alternate_emails_value'] ?? '';
            unset($validatedData['alternate_mobiles_value'], $validatedData['alternate_emails_value']);
            

            $lead = Lead::create([
                "company_id" => $validatedData['company_id'],
                "full_name" => $validatedData['full_name'],
                "primary_mobile" => $validatedData['primary_mobile'],
                "primary_email" => $validatedData['primary_email'],
                "created_by" => $this->user->id,
            ]);

            if($lead){
                if (!empty($alternateMobiles)) {
                    $mobileToInsert = [];
                    $now = now();
    
                    foreach ($alternateMobiles as $differentMobile) {
                        $mobileToInsert[] = [
                            'lead_id' => $lead->id,
                            'company_id' => $lead->company_id,
                            'status' => '1',
                            'mobile' => $differentMobile,
                            'created_by' => Auth::id(),
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
    
                    $extraMobile = ExtraMobile::insert($mobileToInsert);
                    if (!$extraMobile) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Unable To Add Extra Mobile Number.',
                        ]);
                    }
                }
                if (!empty($alternateEmails)) {
                    $emailToInsert = [];
                    $now = now();
                    foreach ($alternateEmails as $differentEmail) {
                        $emailToInsert[] = [
                            'lead_id' => $lead->id,
                            'company_id' => $lead->company_id,
                            'status' => '1',
                            'email' => $differentEmail,
                            'created_by' => Auth::id(),
                            'created_at' => $now,
                            'updated_at' => $now
                        ];
                    }
                    $extraEmail = ExtraEmail::insert($emailToInsert);
                    if (!$extraEmail) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Unable To Add Extra Email.',
                        ]);
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Step 1 Completed.',
                    'company_id' => $lead->company_id,
                    'lead_id' => $lead->id,
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to Create Lead Step 1.'
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error ' . $exception->getMessage().$exception->getLine()
            ]);
        }
    }
    public function addLeadStep2(Request $request)
    {
        try {
            $addLeadStep2Data = (object) $request->add_lead_step2_data;
            $hiddenCompanyLead = (object) $request->hidden_company_lead;
            $request->validate([
                'add_lead_step2_data.lead_main_profession_id' => 'required',
                'add_lead_step2_data.lead_sub_profession_id' => 'required'
            ]);
            $lead = Lead::where('id', $hiddenCompanyLead->lead_id)->update([
                'lead_main_profession_id' => $addLeadStep2Data->lead_main_profession_id,
                'lead_sub_profession_id' => $addLeadStep2Data->lead_sub_profession_id,
                'lead_company' => $addLeadStep2Data->lead_company,
                'lead_designation' => $addLeadStep2Data->lead_designation
            ]);
            if ($lead) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Step 2 Completed.',
                    'company_id' => $hiddenCompanyLead->company_id,
                    'lead_id' => $hiddenCompanyLead->lead_id
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to Create Lead Step 2.'
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error' . $exception->getMessage()
            ]);
        }
    }
    public function addLeadStep3(Request $request)
    {
        try {
            $addLeadStep3Data = $request->add_lead_step3_data;
            $addLeadStep3Data['created_by'] = Auth::id();
            $addLeadStep3Data['associate_id'] = Auth::id();
            $source = Source::create($addLeadStep3Data);
            if ($source) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Step 3 Completed.',
                    'company_id' => $addLeadStep3Data['company_id'],
                    'lead_id' => $addLeadStep3Data['lead_id']
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to Create Lead Step 3.'
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error '
            ]);
        }
    }
    public function addLeadStep4(Request $request)
    {
        try {
            $addLeadStep4Data = $request->add_lead_step4_data;
            $addLeadStep4Data['created_by'] = Auth::id();
            $salePreference = SalePreference::create($addLeadStep4Data);
            if ($salePreference) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Completed.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to Create Lead.'
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error '
            ]);
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
            $salesProfessionData = SalesProfession::query()->where(function ($query) use ($selectedId) {
                $query->where('company_id', $selectedId)
                    ->where('status', 1)
                    ->where('is_parent', 1);
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
                    ->where('is_parent', 1);
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
            $company_id_title_duplicate_check = $modal::where('company_id', $filteredSubTableData['company_id'])
                ->where('title', $filteredSubTableData['title'])
                ->when($param_for_edit, function ($query, $param_for_edit) {
                    $query->where('id', '!=', $param_for_edit);
                })
                ->exists();
            if ($company_id_title_duplicate_check) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Company and Title Already Exists'
                ]);
            }
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
        $result = $this->deleteMultiple(SalesLeadApartmentType::class, DeleteTypeMultipleHistory::class, $ids, 'back-end.sales.sales-lead-apartment-type-list', 'output_sales_lead_apartment_type');
        return response()->json($result);
    }
    public function deleteSalesLeadApartmentSizeMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadApartmentSize::class, DeleteSalesApartmentSizeMultipleHistory::class, $ids, 'back-end.sales.sales-lead-apartment-size-list', 'output_sales_lead_apartment_size');
        return response()->json($result);
    }
    public function deleteSalesLeadViewMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadView::class, DeleteSalesLeadViewMultipleHistory::class, $ids, 'back-end.sales.sales-lead-view-list', 'output_sales_lead_view');
        return response()->json($result);
    }
    public function deleteSalesLeadBudgetMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadBudget::class, DeleteSalesLeadBudgetMultipleHistory::class, $ids, 'back-end.sales.sales-lead-budget-list', 'output_sales_lead_budget');
        return response()->json($result);
    }
    public function deleteSalesLeadSourceMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadSourceInfo::class, DeleteSalesLeadSourceMultipleHistory::class, $ids, 'back-end.sales.sales-lead-source-info-list', 'output_sales_lead_source_info');
        return response()->json($result);
    }
    public function deleteSalesLeadProfessionMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesProfession::class, DeleteSalesLeadProfessionMultipleHistory::class, $ids, 'back-end.sales.sales-lead-profession-list', 'output_sales_lead_profession');
        return response()->json($result);
    }
    public function deleteSalesLeadLocationMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadLocationInfo::class, DeleteSalesLeadLocationMultipleHistory::class, $ids, 'back-end.sales.sales-lead-location-info-list', 'output_sales_lead_location_info');
        return response()->json($result);
    }
    public function deleteSalesLeadFloorMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadFloor::class, DeleteSalesLeadFloorMultipleHistory::class, $ids, 'back-end.sales.sales-lead-floor-list', 'output_sales_lead_floor');
        return response()->json($result);
    }
    public function deleteSalesLeadStatusMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadStatusInfo::class, DeleteSalesLeadStatusMultipleHistory::class, $ids, 'back-end.sales.sales-lead-status-info-list', 'output_sales_lead_status_info');
        return response()->json($result);
    }
    public function deleteSalesLeadFacingMultiple(Request $request)
    {
        $ids = $request->selected;
        $result = $this->deleteMultiple(SalesLeadFacing::class, DeleteSalesLeadFacingMultipleHistory::class, $ids, 'back-end.sales.sales-lead-facing-list', 'output_sales_lead_facing');
        return response()->json($result);
    }
    private function deleteMultiple($modal, $modalHistory, $ids, $blade, $output)
    {
        try {
            DB::beginTransaction();
            $modalHistoryData = $modal::whereIn('id', $ids)->get();
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
                if (property_exists($item, 'parent_id')) {
                    $commonData['parent_id'] = $item->parent_id ?? null;
                }

                if (isset($item->is_parent) && $item->is_parent == 0) {
                    $commonData['is_parent'] = 0;
                }
                return $commonData;
            })->toArray();
            $historyCreate = $modalHistory::insert($historyProcess);
            $multipleDelete = $modal::whereIn('id', $ids)->delete();
            if ($historyCreate && $multipleDelete) {
                $getSaleSubTableData = $this->getDataOfApartment($modal);
                $view = view($blade, compact('getSaleSubTableData'))->render();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Deleted Successfully',
                    'data' => $view,
                    'output' => $output
                ]);
            } else {
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
    public function getSaleEmployeeEntry(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $data = $request->input('sales_employee_entry_data');
                $company_id = $data['company_id'];
                $users = $data['user'];
                $leader_id_of_specific_company = $data['leader_id_of_specific_company'];
                if (empty($company_id) || empty($users) || empty($leader_id_of_specific_company)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Please Fill Required Field',
                    ]);
                }
                $sales_lead_employee_duplicate_check = SalesEmployeeEntry::where('company_id', $company_id)
                    ->whereIn('employee_id', $users)
                    ->where('leader_id', $leader_id_of_specific_company)
                    ->exists();
                if ($sales_lead_employee_duplicate_check) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Company and User Already Exists'
                    ]);
                }
                $now = now();
                $processed_sales_employee_entry_data = collect($users)->map(function ($user_id) use ($company_id, $now, $leader_id_of_specific_company) {
                    return [
                        'company_id' => $company_id,
                        'employee_id' => $user_id,
                        'leader_id' => $leader_id_of_specific_company,
                        'created_by' => auth()->user()->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                })->toArray();
                $sales_employee_entry = SalesEmployeeEntry::insert($processed_sales_employee_entry_data);
                $fetchedSaleEmployeeEntryData = $this->fetchSaleEmployeeEntry();
                // $fetchedSaleLeaderEntryData = $this->fetchSaleLeaderEntry();
                $view = view('back-end.sales._sell-employee-entry', compact('fetchedSaleEmployeeEntryData'))->render();
                if ($sales_employee_entry && $fetchedSaleEmployeeEntryData) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Sales Employee Entry Added Successfully ',
                        'data' => $view
                    ]);
                } else {
                    throw new Exception('Operation Failed');
                }
            } catch (\Throwable $exception) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Operation Failed ' . $exception->getMessage()
                ]);
            }
        } else if ($request->isMethod('get')) {
            try {
                $companies = $this->getCompany()->get();
                $fetchedSaleEmployeeEntryData = SalesEmployeeEntry::with(['company', 'user', 'createdByUser', 'leader'])->get();
                $fetchedSaleLeaderEntryData = SalesLeaderEntry::with(['company', 'user', 'createdByUser'])->get();
                if ($companies && $fetchedSaleEmployeeEntryData && $fetchedSaleLeaderEntryData) {
                    return view('back-end.sales.sell-employee-entry', compact('companies', 'fetchedSaleEmployeeEntryData', 'fetchedSaleLeaderEntryData'));
                } else {
                    throw new Exception('Operation Failed');
                }
            } catch (\Throwable $exception) {
                return back()->with('error', $exception->getMessage())->withInput();
            }

        }
    }
    private function fetchSaleEmployeeEntry()
    {
        return SalesEmployeeEntry::with(['company', 'user', 'createdByUser', 'leader'])->get();
    }
    private function fetchSaleLeaderEntry()
    {
        return SalesLeaderEntry::with(['company', 'user', 'createdByUser'])->get();
    }
    public function getSaleLeaderEntry(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $data = $request->input('sales_leader_entry_data');
                $company_id = $data['company_id'];
                $leaders = $data['leader'];
                if (empty($company_id) || empty($leaders)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Please Fill Required Field',
                    ]);
                }
                $sales_lead_entry_duplicate_check = SalesLeaderEntry::where('company_id', $company_id)
                    ->whereIn('employee_id', $leaders)
                    ->exists();
                if ($sales_lead_entry_duplicate_check) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Company and User Already Exists'
                    ]);
                }
                $now = now();
                $processed_sales_leader_entry_data = collect($leaders)->map(function ($leader_id) use ($company_id, $now) {
                    return [
                        'company_id' => $company_id,
                        'employee_id' => $leader_id,
                        'created_by' => auth()->user()->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                })->toArray();
                $sales_leader_entry = SalesLeaderEntry::insert($processed_sales_leader_entry_data);
                $fetchedSaleLeaderEntryData = $this->fetchSaleLeaderEntry();
                $view = view('back-end.sales._sell-leader-entry', compact('fetchedSaleLeaderEntryData'))->render();
                if ($sales_leader_entry && $fetchedSaleLeaderEntryData) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Sales Leader Entry Added Successfully ',
                        'data' => $view
                    ]);
                } else {
                    throw new Exception('Operation Failed');
                }
            } catch (\Throwable $exception) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Operation Failed ' . $exception->getMessage()
                ]);
            }
        }
    }
    public function getSaleCompanyWiseLeader(Request $request)
    {
        $get_sales_leader = SalesLeaderEntry::with(['company', 'user', 'createdByUser'])->where('company_id', $request->company_id)->get();
        if ($get_sales_leader) {
            return response()->json([
                'status' => 'success',
                'message' => 'Successfull',
                'get_sales_leader' => $get_sales_leader
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed '
            ]);
        }
    }
    public function getSaleEmployeeEntryEdit(Request $request)
    {
        $sales_employee_entry_edit = SalesLeaderEntry::with('user')->select('id', 'employee_id')->get();
        if ($sales_employee_entry_edit) {
            return response()->json([
                'status' => 'success',
                'message' => 'Successfull',
                'sales_employee_entry_edit' => $sales_employee_entry_edit
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed '
            ]);
        }
    }
    public function getSaleEmployeeEntryLeaderUpdate(Request $request)
    {
        try {
            DB::beginTransaction();
            $update_id = $request->update_id;
            $sales_employee_entry_leader_update = SalesEmployeeEntry::where('id', $request->update_id)->update([
                'leader_id' => $request->update_leader
            ]);
            $fetchedSaleEmployeeEntryData = $this->fetchSaleEmployeeEntry();
            $salesEmployeeLeaderHistory = $fetchedSaleEmployeeEntryData->where('id', $request->update_id)->first();
            $sales_employee_entry_leader_history = SalesEmployeeEntryLeaderEditHistory::create([
                'company_id' => $salesEmployeeLeaderHistory->company_id,
                'employee_id' => $salesEmployeeLeaderHistory->employee_id,
                'leader_id' => $salesEmployeeLeaderHistory->leader_id,
                'previous_leader_created_time' => $salesEmployeeLeaderHistory->created_at,
                'current_leader_created_time' => now(),
                'created_by' => auth()->user()->id
            ]);
            $view = view('back-end.sales._sell-employee-entry', compact('fetchedSaleEmployeeEntryData'))->render();
            if ($sales_employee_entry_leader_update && $sales_employee_entry_leader_history) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Updated Successfully',
                    'data' => $view
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Operation Failed '
                ]);
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed ' . $exception->getMessage()
            ]);
        }
    }
    public function deleteSalesEmployeeEntryMultiple(Request $request)
    {
        try {
            DB::beginTransaction();
            $ids = $request->selected;
            $salesEmployeeEntries = SalesEmployeeEntry::whereIn('id', $ids)->get();
            Log::info(json_encode($salesEmployeeEntries, JSON_PRETTY_PRINT));
            $now = now();
            $historyProcess = $salesEmployeeEntries->map(function ($item) use ($now) {
                $data = [
                    'old_id' => $item->id,
                    'old_created_by' => $item->created_by,
                    'old_updated_by' => $item->updated_by,
                    'old_created_at' => $item->created_at,
                    'old_updated_at' => $item->updated_at,
                    'created_by' => Auth::id(),
                    'updated_by' => null,
                    'company_id' => $item->company_id,
                    'employee_id' => $item->employee_id,
                    'leader_id' => $item->leader_id,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                return $data;
            })->toArray();
            $historyCreate = DeleteSalesEmployeeEntryMultipleHistory::insert($historyProcess);
            $multipleDelete = SalesEmployeeEntry::whereIn('id', $ids)->delete();
            if ($historyCreate && $multipleDelete) {
                $fetchedSaleEmployeeEntryData = $this->fetchSaleEmployeeEntry();
                $view = view('back-end.sales._sell-employee-entry', compact('fetchedSaleEmployeeEntryData'))->render();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Deleted Successfully',
                    'data' => $view,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Delation Fail',
                ]);
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed ' . $exception->getMessage()
            ]);
        }

    }
    public function deleteSalesLeaderEntryMultiple(Request $request)
    {
        try {
            DB::beginTransaction();
            $ids = $request->selected;
            $salesLeaderEntries = SalesLeaderEntry::whereIn('id', $ids)->get();
            $now = now();
            $historyProcess = $salesLeaderEntries->map(function ($item) use ($now) {
                $data = [
                    'old_id' => $item->id,
                    'old_created_by' => $item->created_by,
                    'old_updated_by' => $item->updated_by,
                    'old_created_at' => $item->created_at,
                    'old_updated_at' => $item->updated_at,
                    'created_by' => Auth::id(),
                    'updated_by' => null,
                    'company_id' => $item->company_id,
                    'employee_id' => $item->employee_id,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                return $data;
            })->toArray();
            $historyCreate = DeleteSalesLeaderEntryMultipleHistory::insert($historyProcess);
            $multipleDelete = SalesLeaderEntry::whereIn('id', $ids)->delete();
            if ($historyCreate && $multipleDelete) {
                $fetchedSaleLeaderEntryData = $this->fetchSaleLeaderEntry();
                $view = view('back-end.sales._sell-leader-entry', compact('fetchedSaleLeaderEntryData'))->render();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data Deleted Successfully',
                    'data' => $view,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data Delation Fail',
                ]);
            }
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed ' . $exception->getMessage()
            ]);
        }

    }
    public function getSalesProfessionMainProfession(Request $request)
    {
        try {
            $salesLeadProfession = SalesProfession::where('company_id', $request->company_id)->select('id', 'title', 'parent_id', 'is_parent')->get();
            $mainProfession = $salesLeadProfession->where('is_parent', 1)->values();
            $profession = $salesLeadProfession->where('is_parent', '!=', 1)->values();
            if ($mainProfession->isNotEmpty() && $profession->isNotEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'data' => [
                        'mainProfession' => $mainProfession,
                        'profession' => $profession
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error '
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed '
            ]);
        }
    }
    public function getSalesSourceMainSource(Request $request)
    {
        try {
            $salesLeadSourceInfo = SalesLeadSourceInfo::where('company_id', $request->company_id)->select('id', 'title', 'parent_id', 'is_parent')->get();
            $mainSource = $salesLeadSourceInfo->where('is_parent', 1)->values();
            $source = $salesLeadSourceInfo->where('is_parent', '!=', 1)->values();
            if ($mainSource->isNotEmpty() && $source->isNotEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'data' => [
                        'mainSource' => $mainSource,
                        'source' => $source
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Check Error '
                ]);
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed '
            ]);
        }
    }
    public function getSalesPreferenceDropdowns(Request $request)
    {
        try {
            $salesLeadApartmentType = SalesLeadApartmentType::where('company_id', $request->company_id)->select('id', 'title')->get();
            $salesLeadApartmentSize = SalesLeadApartmentSize::where('company_id', $request->company_id)->select('id', 'title')->get();
            $salesLeadFloor = SalesLeadFloor::where('company_id', $request->company_id)->select('id', 'title')->get();
            $salesLeadFacing = SalesLeadFacing::where('company_id', $request->company_id)->select('id', 'title')->get();
            $salesLeadView = SalesLeadView::where('company_id', $request->company_id)->select('id', 'title')->get();
            $salesLeadBudget = SalesLeadBudget::where('company_id', $request->company_id)->select('id', 'title')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Success',
                'data' => [
                    'salesLeadApartmentType' => $salesLeadApartmentType ?? '',
                    'salesLeadApartmentSize' => $salesLeadApartmentSize ?? '',
                    'salesLeadFloor' => $salesLeadFloor ?? '',
                    'salesLeadFacing' => $salesLeadFacing ?? '',
                    'salesLeadView' => $salesLeadView ?? '',
                    'salesLeadBudget' => $salesLeadBudget ?? '',
                ]
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Operation Failed '
            ]);
        }
    }
}
