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
use Throwable;
use Carbon\Carbon;

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
            $permission = $this->permissions()->add_sales_lead;
            if ($request->isMethod('post')) {
                return $this->storeLead($request);
            }
            // $depts = department::where('status', 1)->get();
            // $branches = branch::where('status', 1)->get();
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            $salesEmployeeEntries = SalesEmployeeEntry::with(['user'])->where('company_id',$this->user->company_id)->get();
            
            // $leadWiseLocations = [
            //     ['id' => 1, 'dept_name' => 'Dhaka'],
            //     ['id' => 2, 'dept_name' => 'Narayanganj'],
            //     ['id' => 3, 'dept_name' => 'Rupganj'],
            //     ['id' => 4, 'dept_name' => 'Dhanmondi'],
            // ];
            return view('back-end/sales/add-lead', compact('companies','salesEmployeeEntries'));
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
                ['op_name' => $request->input('op_name')],
            );
            $rules = [
                "company_id" => ['required', 'integer', 'exists:company_infos,id'],
                "full_name" => ['required', 'string', 'max:255'],
                'primary_mobile' => [
                    'required',
                    'regex:/^\+?[0-9]{9,13}$/',
                    Rule::unique('sales_leads', 'primary_mobile')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->ignore($request->input('lead_id')),
                    Rule::unique('sales_lead_extra_mobiles', 'mobile')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->whereNot('lead_id', $request->input('lead_id'))
                ],
                'primary_email' => [
                    'required',
                    'email',
                    Rule::unique('sales_leads', 'primary_email')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->ignore($request->input('lead_id')),
                    Rule::unique('sales_lead_extra_emails', 'email')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->whereNot('lead_id', $request->input('lead_id'))
                ],
                'alternate_mobiles_value' => ['array'],
                'alternate_mobiles_value.*' => [
                    'required',
                    'regex:/^\+?[0-9]{9,13}$/',
                    'distinct',
                    Rule::unique('sales_leads', 'primary_mobile')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->ignore($request->input('lead_id')),
                    Rule::unique('sales_lead_extra_mobiles', 'mobile')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->whereNot('lead_id', $request->input('lead_id')),
                    Rule::notIn([$request->add_lead_step1_data['primary_mobile']])
                ],
                'alternate_emails_value' => ['array'],
                'alternate_emails_value.*' => [
                    'required',
                    'email',
                    'distinct',
                    Rule::unique('sales_leads', 'primary_email')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->ignore($request->input('lead_id')),
                    Rule::unique('sales_lead_extra_emails', 'email')
                        ->where('company_id', $request->add_lead_step1_data['company_id'])
                        ->whereNot('lead_id', $request->input('lead_id')),
                    Rule::notIn([$request->add_lead_step1_data['primary_email']])
                ],
                'op_name' => ['required', 'string', 'in:create,update'],
                'lead_id' => ['sometimes', 'nullable', 'exists:sales_leads,id'],
            ];
            $messages = [
                'company_id.required' => 'Company is Required',

                'primary_mobile.required' => 'Primary Mobile is Required',
                'primary_mobile.unique' => 'Primary Mobile already exist for this company',

                'primary_email.required' => 'Primary Email is Required',
                'primary_email.unique' => 'Primary Email already exist for this company',
                'primary_email.email' => 'Email Format is Invalid',

                'alternate_mobiles_value.0.required' => 'Alternate Mobile 1 is required.',
                'alternate_mobiles_value.0.regex' => 'Alternate Mobile 1 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.0.unique' => 'Alternate Mobile 1 already exist for this company.',

                'alternate_mobiles_value.1.required' => 'Alternate Mobile 2 is required.',
                'alternate_mobiles_value.1.regex' => 'Alternate Mobile 2 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.1.unique' => 'Alternate Mobile 2 already exist for this company.',

                'alternate_mobiles_value.2.required' => 'Alternate Mobile 3 is required.',
                'alternate_mobiles_value.2.regex' => 'Alternate Mobile 3 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.2.unique' => 'Alternate Mobile 3 already exist for this company.',

                'alternate_mobiles_value.3.required' => 'Alternate Mobile 4 is required.',
                'alternate_mobiles_value.3.regex' => 'Alternate Mobile 4 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.3.unique' => 'Alternate Mobile 4 already exist for this company.',

                'alternate_mobiles_value.4.required' => 'Alternate Mobile 5 is required.',
                'alternate_mobiles_value.4.regex' => 'Alternate Mobile 5 must be a valid number (9–13 digits)',
                'alternate_mobiles_value.4.unique' => 'Alternate Mobile 5 already exist for this company.',

                'alternate_emails_value.0.required' => 'Alternate Email 1 is required.',
                'alternate_emails_value.0.unique' => 'Alternate Email 1 already exist for this company.',

                'alternate_emails_value.1.required' => 'Alternate Email 2 is required.',
                'alternate_emails_value.1.unique' => 'Alternate Email 2 already exist for this company.',

                'alternate_emails_value.2.required' => 'Alternate Email 3 is required.',
                'alternate_emails_value.2.unique' => 'Alternate Email 3 already exist for this company.',

                'alternate_emails_value.3.required' => 'Alternate Email 4 is required.',
                'alternate_emails_value.3.unique' => 'Alternate Email 4 already exist for this company.',

                'alternate_emails_value.4.required' => 'Alternate Email 5 is required.',
                'alternate_emails_value.4.unique' => 'Alternate Email 5 already exist for this company.',

                'alternate_mobiles_value.0.distinct' => 'Alternate Mobile 1 must be unique.',
                'alternate_mobiles_value.1.distinct' => 'Alternate Mobile 2 must be unique.',
                'alternate_mobiles_value.2.distinct' => 'Alternate Mobile 3 must be unique.',
                'alternate_mobiles_value.3.distinct' => 'Alternate Mobile 4 must be unique.',
                'alternate_mobiles_value.4.distinct' => 'Alternate Mobile 5 must be unique.',

                'alternate_mobiles_value.0.not_in' => 'Alternate Mobile 1 must not match with primary mobile.',
                'alternate_mobiles_value.1.not_in' => 'Alternate Mobile 2 must not match with primary mobile.',
                'alternate_mobiles_value.2.not_in' => 'Alternate Mobile 3 must not match with primary mobile.',
                'alternate_mobiles_value.3.not_in' => 'Alternate Mobile 4 must not match with primary mobile.',
                'alternate_mobiles_value.4.not_in' => 'Alternate Mobile 5 must not match with primary mobile.',

                'alternate_emails_value.0.distinct' => 'Alternate Email 1 must be unique.',
                'alternate_emails_value.1.distinct' => 'Alternate Email 2 must be unique.',
                'alternate_emails_value.2.distinct' => 'Alternate Email 3 must be unique.',
                'alternate_emails_value.3.distinct' => 'Alternate Email 4 must be unique.',
                'alternate_emails_value.4.distinct' => 'Alternate Email 5 must be unique.',

                'alternate_emails_value.0.not_in' => 'Alternate Email 1 must not match with primary email.',
                'alternate_emails_value.1.not_in' => 'Alternate Email 2 must not match with primary email.',
                'alternate_emails_value.2.not_in' => 'Alternate Email 3 must not match with primary email.',
                'alternate_emails_value.3.not_in' => 'Alternate Email 4 must not match with primary email.',
                'alternate_emails_value.4.not_in' => 'Alternate Email 5 must not match with primary email.',

                'alternate_emails_value.0.email' => 'Alternate Email 1 Format is Invalid.',
                'alternate_emails_value.1.email' => 'Alternate Email 2 Format is Invalid.',
                'alternate_emails_value.2.email' => 'Alternate Email 3 Format is Invalid.',
                'alternate_emails_value.3.email' => 'Alternate Email 4 Format is Invalid.',
                'alternate_emails_value.4.email' => 'Alternate Email 5 Format is Invalid.',
            ];
            $validator = Validator::make($input, $rules, $messages);
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
            $leadDataInput = [
                "company_id" => $validatedData['company_id'],
                "full_name" => $validatedData['full_name'],
                "primary_mobile" => $validatedData['primary_mobile'],
                "primary_email" => $validatedData['primary_email'],
                "spouse" => $request->add_lead_step1_data['spouse'],
                "notes" => $request->add_lead_step1_data['notes'],
                "associate_id" => $request->add_lead_step1_data['associate_id'],
            ];

            $leadCreateOrUpdate = null;
            $lead_id = $request->input('lead_id');
            if (isset($lead_id) && $validatedData['op_name'] == 'update') {
                $leadCreateOrUpdate = Lead::find($lead_id);
                if (!$leadCreateOrUpdate) {
                    throw new Exception('Not found!');
                }
                $leadDataInput['updated_by'] = $this->user->id;
                $leadDataInput['updated_at'] = now();
                $leadDataInput['associate_id'] = $leadCreateOrUpdate->associate_id;
                $leadCreateOrUpdate->where('id', $lead_id)->update($leadDataInput);
                $existingMobiles = ExtraMobile::where('lead_id', $lead_id)
                    ->orderBy('id') // ensure a consistent order
                    ->get();
                $existingEmails = ExtraEmail::where('lead_id', $lead_id)
                    ->orderBy('id') // ensure a consistent order
                    ->get();

                $now = now();
                if (!empty($alternateMobiles)) {
                    foreach ($existingMobiles as $extraMobileModel) {
                        $differentMobile = array_shift($alternateMobiles);
                        $extraMobileModel->update([
                            'mobile' => $differentMobile,
                            'updated_by' => $this->user->id,
                            'updated_at' => $now,
                        ]);
                    }

                }

                if (!empty($alternateEmails)) {
                    foreach ($existingEmails as $extraEmailModel) {
                        $differentEmail = array_shift($alternateEmails);
                        $extraEmailModel->update([
                            'email' => $differentEmail,
                            'updated_by' => $this->user->id,
                            'updated_at' => $now,
                        ]);
                    }
                }
                $profession = $this->getSalesProfessionMainProfession($leadCreateOrUpdate->company_id);
                $lead = $leadCreateOrUpdate;
                $view = view('back-end.sales.__add-lead-stage-2', compact('lead', 'profession'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view],
                    'message' => 'Lead Creation Step 1 Completed.'
                ]);
            } else {
                $leadDataInput['created_by'] = $this->user->id;
                $leadDataInput['created_at'] = now();
                $leadDataInput['status'] = 6;
                $lead = Lead::create($leadDataInput);

                if ($lead) {
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
                    $profession = $this->getSalesProfessionMainProfession($lead->company_id);
                    $view = view('back-end.sales.__add-lead-stage-2', compact('lead', 'profession'))->render();
                    return response()->json([
                        'status' => 'success',
                        'data' => ['view' => $view],
                        'message' => 'Lead Creation Step 1 Completed.'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unable to Create Lead Step 1.'
                    ]);
                }
            }
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $exception->getMessage()
            ]);
        }
    }
    public function backLeadStep1(Request $request)
    {
        try {
            $permission = $this->permissions()->add_sales_lead;
            if ($request->ajax() && $request->isMethod('post')) {
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                $salesEmployeeEntries = SalesEmployeeEntry::with(['user'])->where('company_id',$this->user->company_id)->get();
                $getLead = Lead::with(['extraMobiles', 'extraEmails'])->where('id', $request->lead_id)->first();
                $view = view('back-end.sales.__add-lead-form', compact('getLead', 'companies','salesEmployeeEntries'))->render();
                if ($getLead) {
                    return response()->json([
                        'status' => 'success',
                        'data' => ['view' => $view],
                        'message' => 'Request Process Successfull'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unable to get Lead Step 1 Data.'
                    ]);
                }
            }
            throw new Exception('Request Method Not Allowed');
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function backLeadStep2(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $profession = $this->getSalesProfessionMainProfession($request->company_id);
                $lead = Lead::where('id', $request->lead_id)->first();
                $view = view('back-end.sales.__add-lead-stage-2', compact('lead', 'profession', ))->render();
                if ($lead) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data Fetch Successfull.',
                        'data' => [
                            'view' => $view,
                        ],
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unable to get Lead Step 2 Data.'
                    ]);
                }
            }
            throw new Exception('Request Method Not Allowed');
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function backLeadStep3(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $getData = Source::where('lead_id', $request->lead_id)->first();
                $lead = (object) [
                    'id' => $getData->lead_id,
                    'company_id' => $getData->company_id,
                    'main_source_id' => $getData->main_source_id,
                    'sub_source_id' => $getData->sub_source_id,
                    'reference_name' => $getData->reference_name,
                    'created_by' => $getData->created_by,
                    'updated_by' => $getData->updated_by
                ];
                $source = $this->getSalesSourceMainSource($request->company_id);
                $step = 'back_step_3';
                $view = view('back-end.sales.__add-lead-stage-3', compact('lead', 'source', 'step'))->render();
                if ($lead) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data Fetch Successfull.',
                        'data' => [
                            'view' => $view,
                        ],
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unable to get Lead Step 3 Data.'
                    ]);
                }
            }
            throw new Exception('Request Method Not Allowed');
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function addNewLeadForm(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $permission = $this->permissions()->add_sales_lead;
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                $salesEmployeeEntries = SalesEmployeeEntry::with(['user'])->where('company_id',$this->user->company_id)->get();
                $view = view('back-end.sales.__add-lead-form', compact('companies', 'salesEmployeeEntries'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'view' => $view,
                    ],
                ]);
            }
            throw new Exception('Request Method Not Allowed');
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function getLeadStep2(Request $request)
    {
        $leadProfession = Lead::where('id', $request->lead_id)->first();
        if ($leadProfession) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data Fetch Successfull.',
                'data' => $leadProfession,
                'company_id' => $leadProfession->company_id,
                'lead_id' => $leadProfession->lead_id,
                'output_desn' => 'step2'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to get Lead Step 2 Data.'
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
            Lead::where('id', $hiddenCompanyLead->lead_id)->update([
                'lead_main_profession_id' => $addLeadStep2Data->lead_main_profession_id,
                'lead_sub_profession_id' => $addLeadStep2Data->lead_sub_profession_id,
                'lead_company' => $addLeadStep2Data->lead_company,
                'lead_designation' => $addLeadStep2Data->lead_designation,
            ]);
            $existingLead = Source::where('lead_id', $hiddenCompanyLead->lead_id)->first();
            $source = $this->getSalesSourceMainSource($hiddenCompanyLead->company_id);
            $step = null;
            if ($existingLead) {
                $lead = $existingLead;
                $step = 'forward_step_3';
            } else {
                $lead = Lead::find($hiddenCompanyLead->lead_id);
            }
            $view = view('back-end.sales.__add-lead-stage-3', compact('lead', 'source', 'step'))->render();
            if ($lead) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Step 2 Completed.',
                    'data' => ['view' => $view],
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
                'message' => 'Error' . $exception->getMessage().$exception->getLine()
            ]);
        }
    }

    public function addLeadStep3(Request $request)
    {
        try {
            $addLeadStep3Data = $request->add_lead_step3_data;
            $addLeadStep3Data['created_by'] = Auth::id();
            $existingLead = Source::where('lead_id', $addLeadStep3Data['lead_id'])->first();
            // dd($existingLead);
            $preference = $this->getSalesPreferenceDropdowns($addLeadStep3Data['company_id']);
            if ($existingLead) {
                $addLeadStep3Data['created_by'] = Auth::id();
                $addLeadStep3Data['created_at'] = now();
                $leadUpdate = Source::where('lead_id', $addLeadStep3Data['lead_id'])->update($addLeadStep3Data);
                $getPreference = SalePreference::where('lead_id', $addLeadStep3Data['lead_id'])->first();
                $lead = (object) [
                    'id' => $existingLead->lead_id,
                    'company_id' => $existingLead->company_id,
                    'preference_note' => $getPreference->preference_note ?? null,
                    'apartment_type_id' => $getPreference->apartment_type_id ?? null,
                    'apartment_size_id' => $getPreference->apartment_size_id ?? null,
                    'floor_id' => $getPreference->floor_id ?? null,
                    'facing_id' => $getPreference->facing_id ?? null,
                    'view_id' => $getPreference->view_id ?? null,
                    'budget_id' => $getPreference->budget_id ?? null,
                ];
                $view = view('back-end.sales.__add-lead-stage-4', compact('lead', 'preference'))->render();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Step 3 Completed.',
                    'data' => ['view' => $view]
                ]);
            }
            $source = Source::create($addLeadStep3Data);
            if($source){
                $findForStatus = Lead::where('id',$addLeadStep3Data['lead_id'])->first();
                $findForStatus->update([
                    'status'=>7
                ]);
            }
            // dd($source);
            $lead = (object) [
                'id' => $source->lead_id,
                'company_id' => $source->company_id
            ];
            $view = view('back-end.sales.__add-lead-stage-4', compact('lead', 'preference'))->render();
            if ($source) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Step 3 Completed.',
                    'data' => ['view' => $view]
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
                'message' => 'Error ' . $exception->getMessage()
            ]);
        }
    }
    public function addLeadStep4(Request $request)
    {
        try {
            $addLeadStep4Data = $request->add_lead_step4_data;
            $existingLead = SalePreference::where('lead_id', $addLeadStep4Data['lead_id'])->first();
            if ($existingLead) {
                $addLeadStep4Data['created_by'] = Auth::id();
                $addLeadStep4Data['created_at'] = now();
                SalePreference::where('lead_id', $addLeadStep4Data['lead_id'])->update($addLeadStep4Data);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Lead Creation Completed.'
                ]);
            }
            $addLeadStep4Data['created_by'] = Auth::id();
            $salePreference = SalePreference::create($addLeadStep4Data);
            if($salePreference){
                $findForStatus = Lead::where('id',$addLeadStep4Data['lead_id'])->first();
                $findForStatus->update([
                    'status'=>1
                ]);
            }
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

    public function leadList(Request $request)
    {
        try {
            $leadListDatum = Lead::with(['source','preference','extraMobiles','extraEmails'])->paginate('10');
            // dd($leadListDatum);
            $formattedLeads = $leadListDatum->map(function ($lead) {
                return [
                    'lead_id' => $lead->id,
                    'creation'=>$lead->created_at?Carbon::parse($lead->created_at)->format('d M,Y'):null,
                    'full_name' => $lead->full_name ?? null,
                    'primary_mobile' => $lead->primary_mobile,
                    'mobiles'=>$lead->extraMobiles?->pluck('mobile')->all(),
                    'associate_name'=>$lead->associate?->name,
                    'notes' => $lead->notes ?? null,
                    'lead_status_id' => $lead->lead_status_id ?? null,
                    'status' => $lead->status ?? null,
                    'sell_status' => $lead->sell_status ?? null,
                ];
            });
            // 'spouse' => $lead->spouse ?? null,
            // 'primary_email' => $lead->primary_email,
            // 'associate_id' => $lead->associate_id ?? null,
            // 'lead_company' => $lead->lead_company ?? null,
            // 'lead_designation' => $lead->lead_designation ?? null,
            // 'created_by' => $lead->created_by,
            // 'updated_by' => $lead->updated_by,
            // 'created_at' => $lead->created_at,
            // 'updated_at' => $lead->updated_at,
            // 'lead_main_profession_id'=>$lead->lead_main_profession_id,
            // 'lead_sub_profession_id'=>$lead->lead_sub_profession_id,
            // 'main_profession'=>$lead->leadMainProfession?->title,
            // 'sub_profession'=>$lead->leadSubProfession?->title,
            // 'main_source'=>$lead->source?->leadMainSource?->title,
            // 'sub_source'=>$lead->source?->leadSubSource?->title,
            // 'reference_name'=>$lead->source->reference_name ?? null,
            // 'preference_note'=>$lead->preference->preference_note ?? null,
            // 'apartment_type_name'=>$lead->preference?->apartmentType?->title,
            // 'apartment_size_name'=>$lead->preference?->apartmentSize?->title,
            // 'apartment_size'=>$lead->preference?->apartmentSize?->size,
            // 'apartment_floor'=>$lead->preference?->floor?->title,
            // 'apartment_facing'=>$lead->preference?->facing?->title,
            // 'apartment_view'=>$lead->preference?->view?->title,
            // 'apartment_budget'=>$lead->preference?->budget?->title,
            // 'emails'=>$lead->extraEmails?->pluck('email')->all(),
            // 'associate_employee_id'=>$lead->associate?->employee_id,

            //  dd($formattedLeads);
            return view('back-end.sales.sales-lead-list',compact('formattedLeads','leadListDatum'));
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
    public function getSalesProfession(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $lead_profession_parent_id = $request->input('lead_profession_parent_id');
                $profession = SalesProfession::where('parent_id', $lead_profession_parent_id)->get();
                return response()->json([
                    'profession' => $profession
                ]);
            }
            throw new Exception('Request Method Not Allowed');
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function getSalesSource(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $lead_source_parent_id = $request->input('lead_source_parent_id');
                $source = SalesLeadSourceInfo::where('parent_id', $lead_source_parent_id)->get();
                return response()->json([
                    'source' => $source
                ]);
            }
            throw new Exception('Request Method Not Allowed');
        } catch (Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    private function getSalesProfessionMainProfession($company_id)
    {
        try {
            $salesLeadProfession = SalesProfession::where('company_id', $company_id)->select('id', 'title', 'parent_id', 'is_parent')->get();
            $mainProfession = $salesLeadProfession->where('is_parent', 1)->values();
            $profession = $salesLeadProfession->where('is_parent', '!=', 1)->values();
            return (object) [
                'mainProfessions' => $mainProfession,
                'professions' => $profession
            ];
        } catch (\Throwable $exception) {
            return (object) [
                'mainProfession' => collect(),
                'profession' => collect(),
                'error' => 'Failed to fetch sales professions.'
            ];
        }
    }
    public function getSalesSourceMainSource($company_id)
    {
        $salesLeadSourceInfo = SalesLeadSourceInfo::where('company_id', $company_id)->select('id', 'title', 'parent_id', 'is_parent')->get();
        $mainSource = $salesLeadSourceInfo->where('is_parent', 1)->values();
        $source = $salesLeadSourceInfo->where('is_parent', '!=', 1)->values();
        if ($mainSource->isNotEmpty() && $source->isNotEmpty()) {
            return (object) [
                'mainSource' => $mainSource,
                'source' => $source
            ];
        }
    }
    public function getSalesPreferenceDropdowns($company_id)
    {
        $salesLeadApartmentType = SalesLeadApartmentType::where('company_id', $company_id)->select('id', 'title')->get();
        $salesLeadApartmentSize = SalesLeadApartmentSize::where('company_id', $company_id)->select('id', 'title')->get();
        $salesLeadFloor = SalesLeadFloor::where('company_id', $company_id)->select('id', 'title')->get();
        $salesLeadFacing = SalesLeadFacing::where('company_id', $company_id)->select('id', 'title')->get();
        $salesLeadView = SalesLeadView::where('company_id', $company_id)->select('id', 'title')->get();
        $salesLeadBudget = SalesLeadBudget::where('company_id', $company_id)->select('id', 'title')->get();
        return (object) [
            'salesLeadApartmentType' => $salesLeadApartmentType ?? '',
            'salesLeadApartmentSize' => $salesLeadApartmentSize ?? '',
            'salesLeadFloor' => $salesLeadFloor ?? '',
            'salesLeadFacing' => $salesLeadFacing ?? '',
            'salesLeadView' => $salesLeadView ?? '',
            'salesLeadBudget' => $salesLeadBudget ?? '',
        ];
    }
}
