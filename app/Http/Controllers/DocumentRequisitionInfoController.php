<?php

namespace App\Http\Controllers;

use App\Models\company_info;
use App\Models\department;
use App\Models\Project_document_requisition_info;
use App\Models\Project_wise_data_type_required_info;
use App\Models\Project_wise_data_type_required_infos_delete_history;
use App\Models\Required_data_type_upload_responsible_user_info;
use App\Models\Required_data_type_upload_responsible_user_infos_delete_history;
use App\Models\User;
use App\Models\VoucherType;
use App\Notifications\DataArchiveProjectAssignedSummaryNotification;
use App\Notifications\DataArchiveResponsibleUserAssigned;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DocumentRequisitionInfoController extends Controller
{
    use ParentTraitCompanyWise;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        try {
            $permission = $this->permissions()->project_document_requisition_report;
            if ($request->ajax()) {
                return $this->report($request, $permission);
            }
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.requisition.report', compact('companies'))->render();
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    private function report(Request $request, $permission)
    {
        try {
            if ($request->isMethod('post')) {
                $validatedData = $request->validate([
                    'company' => ['required', 'string', 'exists:company_infos,id'],
                    'projects' => ['required', 'array'],
                    'projects.*' => ['required', 'string', 'exists:branches,id'],
                ]);
                extract($validatedData);
                $branches = $this->getUserProjectPermissions($this->user->id, $permission)->where('status', 1)->where('company_id', $company)->whereIn('id', $projects)->get();
                $data = (object)$branches->map(function ($branch) {
                    return (object)[
                        'project_id' => $branch->id,
                        'company_id' => $branch->company_id,
                        'company_name' => $branch->company->company_name,
                        'project_name' => $branch->branch_name,
                        'project_address' => $branch->address,
                        'pdri_id' => $branch->documentRequiredInfo->id ?? null,
                        'pdri_subject' => $branch->documentRequiredInfo->message_subject ?? null,
                        'pdri_details' => $branch->documentRequiredInfo->message_body ?? null,
                        'created_by' => $branch->documentRequiredInfo->createdBy->name ?? null,
                        'updated_by' => $branch->documentRequiredInfo->updatedBy->name ?? null,
                        'created_at' => date('d-F-y H:i:s A', strtotime(@$branch->documentRequiredInfo->created_at)) ?? null,
                        'updated_at' => date('d-F-y H:i:s A', strtotime(@$branch->documentRequiredInfo->updated_at)) ?? null,
                        'data_type_required_count' => $branch->documentRequiredInfo?->dataTypeRequired->count() ?? 0,
                    ];
                });
                if ($data->isEmpty()) {
                    throw new \Exception('No data found!');
                }
                $view = view('back-end.requisition._project_wise_data_type_report', compact('data'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view],
                    'message' => 'Request processed successfully.'
                ]);
            }
            throw new \Exception('Request method not allowed');
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    private function projectWiseDataTypeReportDetailsData($id, $project_id, $company_id)
    {
        $data = Project_document_requisition_info::with([
            'project:id,branch_name',
            'company:id,company_name',
            'dataTypeRequired:id,pdri_id,data_type_id,status,deadline,created_at',
            'dataTypeRequired.archiveDataType:id,voucher_type_title',
            'dataTypeRequired.responsibleBy.user:id,name,employee_id,dept_id',
            'dataTypeRequired.responsibleBy.user.department:id,dept_name,dept_code',
            'dataTypeRequired.archiveDataType.accountVoucher' => function ($query) use ($project_id) {
                $query->select('id', 'voucher_type_id', 'project_id') // Add other fields you need
                ->where('project_id', $project_id);
            },
            'dataTypeRequired.archiveDataType.accountVoucher.voucherDocuments:id', // Add required fields
        ])
            ->where('company_id', $company_id)
            ->where('project_id', $project_id)
            ->where('id', $id)
            ->first();
        if (!$data) {
            throw new \Exception('No data found!');
        }
        $result = (object)[
            'pdri_id' => $data->id,
            'company_id' => $data->company_id,
            'company_name' => $data->company->company_name,
            'project_name' => $data->project->branch_name,
            'project_id' => $data->project_id,
            'res_dept' => $data->depts,
            'data_types' => $data->dataTypeRequired->map(function ($dataTypeRequired) {
                $departments = collect();

                foreach ($dataTypeRequired->responsibleBy as $responsible) {
                    if ($responsible->user && $responsible->user->department) {
                        $departments->push($responsible->user->department);
                    }
                }

                $uniqueDepartments = $departments->unique('id')->map(function ($dept) {
                    return (object)[
                        'dept_id' => $dept->id,
                        'dept_name' => $dept->dept_name,
                        'dept_code' => $dept->dept_code,
                    ];
                })->values();

                $documentCount = optional($dataTypeRequired->archiveDataType->accountVoucher)->sum(function ($accountVoucher) {
                    return $accountVoucher->voucherDocuments->count();
                });

                return (object)[
                    'req_data_type_id' => $dataTypeRequired->id,
                    'created_at' => date('d-M-y', strtotime($dataTypeRequired->created_at)),
                    'deadline' => date('d-M-y', strtotime($dataTypeRequired->deadline)),
                    'data_type_id' => optional($dataTypeRequired->archiveDataType)->id,
                    'data_type_name' => optional($dataTypeRequired->archiveDataType)->voucher_type_title,
                    'necessity' => $dataTypeRequired->status,
                    'documents' => $documentCount ?? 0,
                    'responsible_by_count' => $dataTypeRequired->responsibleBy->count(),
                    'responsible_by' => $dataTypeRequired->responsibleBy->map(function ($responsibleBy) {
                        return (object)[
                            'id' => optional($responsibleBy->user)->id,
                            'name' => optional($responsibleBy->user)->name,
                            'employee_id' => optional($responsibleBy->user)->employee_id,
                        ];
                    }),
                    'departments' => $uniqueDepartments,
                ];
            }),
        ];
        return $result;
    }

    public function projectWiseDataTypeReportDetails(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validatedData = $request->validate([
                    'id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                ]);
                extract($validatedData);
                $result = $this->projectWiseDataTypeReportDetailsData($id, $project_id, $company_id);
                $view = view('back-end.requisition._project_wise_data_type_wise_document_status_report_details', compact('result'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view],
                    'message' => 'Request processed successfully.'
                ]);
            }
            throw new \Exception('Request method not allowed');
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->store($request);
            }
            $permission = $this->permissions()->project_document_requisition_entry;
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.requisition.add', compact('companies'))->render();
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    private function store(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $validatedData = $request->validate([
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                    'projects' => ['required', 'array'],
                    'projects.*' => ['required', 'string', 'exists:branches,id'],
                    'data_types' => ['required', 'array'],
                    'data_types.*' => ['required', 'string', 'exists:voucher_types,id'],
                    'users' => ['required', 'array'],
                    'users.*' => ['required', 'string', 'exists:users,id'],
                    'deadline' => ['required', 'string', 'date'],
                    'subject' => ['sometimes', 'nullable', 'string', 'max:255'],
                    'details' => ['sometimes', 'nullable', 'string'],
                ]);

                // Convert arrays to collections
                $projects = collect($validatedData['projects']);
                $data_types = collect($validatedData['data_types']);
                $users = collect($validatedData['users']);

                foreach ($projects as $project_id) {
                    $pdri = Project_document_requisition_info::firstOrCreate(
                        ['project_id' => $project_id],
                        [
                            'company_id' => $validatedData['company_id'],
                            'message_subject' => $validatedData['subject'] ?? null,
                            'message_body' => $validatedData['details'] ?? null,
                            'created_by' => $this->user->id,
                            'updated_by' => null,
                        ]
                    );
                    $userDataMap = []; // user_id => [ ['name' => ..., 'deadline' => ...], ... ]
                    foreach ($data_types as $data_type_id) {
                        $pwdtr = Project_wise_data_type_required_info::firstOrCreate(
                            [
                                'pdri_id' => $pdri->id,
                                'data_type_id' => $data_type_id,
                            ],
                            [
                                'company_id' => $validatedData['company_id'],
                                'deadline' => $validatedData['deadline'],
                                'status' => 1,
                                'created_by' => $this->user->id,
                                'updated_by' => null,
                                'created_at' => now(),
                                'updated_at' => null,
                            ]
                        );

                        $this->responsibleUserEntry($users, $pwdtr, $validatedData['company_id'], $userDataMap);
                    }
                    if (!empty($userDataMap)) {
                        $projectName = optional($pdri->project)->branch_name ?? 'Unknown Project';
                        foreach ($userDataMap as $user_id => $dataTypes) {
                            $user = Cache::remember("user_{$user_id}", 60, fn () => User::find($user_id));
                            $user->notify(new DataArchiveProjectAssignedSummaryNotification(
                                $projectName,
                                $dataTypes,
                                company_info::find($validatedData['company_id'])->company_name,
                                route('add.archive.info'),
                            ));
                        }
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data added successfully.',
                ]);
            }
            throw new \Exception('Request method not allowed');
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }


    public function companyWiseRequiredData(Request $request)
    {
        try {
            $permision = $this->permissions()->requisition;
            if ($request->isMethod('post')) {
                $request->validate([
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                ]);
                extract($request->post());
                $projects = $this->getUserProjectPermissions($this->user->id, $permision)->without('getUsers', 'company')->where('status', 1)->where('company_id', $company_id)->select('id', 'branch_name', 'address')->get();
                $types = $this->archiveTypeList($permision)->without('voucherWithUsers', 'createdBY', 'updatedBY', 'company', 'archiveDocumentInfos', 'archiveDocuments')->where('status', 1)->where('company_id', $company_id)->select('id', 'voucher_type_title', 'code')->get();
                $departments = $this->getDepartment($permision)->without('createdBy', 'updatedBy', 'getUsers', 'company')->where('company_id', $company_id)->where('status', 1)->where('company_id', $company_id)->select('id', 'dept_code', 'dept_name')->get();
                return response()->json([
                    'status' => 'success',
                    'data' => ['projects' => $projects, 'types' => $types, 'departments' => $departments],
                    'message' => 'Request processed successfully.'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Request method not allowed!',
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function projectWiseDataTypeNecessityChange(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $validatedData = $request->validate([
                    'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                    'ids' => ['required', 'array'],
                    'ids.*' => ['required', 'integer', 'exists:project_wise_data_type_required_infos,id'],
                    'value' => ['required', 'string', 'in:0,1'],
                ]);
                extract($validatedData);
                foreach ($ids as $id) {
                    Project_wise_data_type_required_info::where('id', $id)->update([
                        'status' => $value,
                        'updated_by' => $this->user->id,
                        'updated_at' => now(),
                    ]);
                }
                $result = $this->projectWiseDataTypeReportDetailsData($pdri_id, $project_id, $company_id);
                $view = view('back-end.requisition._project_wise_data_type_wise_document_status_report_details', compact('result'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view],
                    'message' => 'Data updated successfully.'
                ]);
            }
            throw new \Exception('Request method not allowed!');
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function projectWiseDataTypeDelete(Request $request)
    {
        try {
            if (!$request->ajax()) {
                throw new \Exception('Request method not allowed!');
            }

            $validatedData = $request->validate([
                'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                'project_id' => ['required', 'string', 'exists:branches,id'],
                'company_id' => ['required', 'string', 'exists:company_infos,id'],
                'ids' => ['required', 'array'],
                'ids.*' => ['required', 'integer', 'exists:project_wise_data_type_required_infos,id'],
            ]);

            foreach ($validatedData['ids'] as $id) {
                $old_data = Project_wise_data_type_required_info::with(['responsibleBy'])->find($id);

                if ($old_data) {
                    $this->projectWiseDataTypeDeleteHistory($old_data);
                    $old_data->responsibleBy()->delete();
                    $old_data->delete();
                }
            }

            $result = $this->projectWiseDataTypeReportDetailsData(
                $validatedData['pdri_id'],
                $validatedData['project_id'],
                $validatedData['company_id']
            );

            $view = view('back-end.requisition._project_wise_data_type_wise_document_status_report_details', compact('result'))->render();

            return response()->json([
                'status' => 'success',
                'data' => ['view' => $view],
                'message' => 'Data deleted successfully.'
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }


    private function projectWiseDataTypeDeleteHistory($old_data)
    {
        try {
            if (!$old_data) {
                throw new \Exception('Old data not found!');
            }

            $responsibleBy = $old_data->responsibleBy;
            $old_responsibleBy = [];

            foreach ($responsibleBy as $responsible) {
                $old_responsibleBy[] = [
                    'old_id' => $responsible->id,
                    'company_id' => $responsible->company_id,
                    'pwdtr_id' => $responsible->pwdtr_id,
                    'user_id' => $responsible->user_id,
                    'old_created_by' => $responsible->created_by,
                    'old_updated_by' => $responsible->updated_by,
                    'old_created_at' => $responsible->created_at,
                    'old_updated_at' => $responsible->updated_at,
                    'created_by' => $this->user->id,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => null,
                ];
            }

            if (!empty($old_responsibleBy)) {
                Required_data_type_upload_responsible_user_infos_delete_history::insert($old_responsibleBy);
            }

            Project_wise_data_type_required_infos_delete_history::create([
                'old_id' => $old_data->id,
                'company_id' => $old_data->company_id,
                'pdri_id' => $old_data->pdri_id,
                'data_type_id' => $old_data->data_type_id,
                'deadline' => $old_data->deadline,
                'status' => $old_data->status,
                'old_created_by' => $old_data->created_by,
                'old_updated_by' => $old_data->updated_by,
                'old_created_at' => $old_data->created_at,
                'old_updated_at' => $old_data->updated_at,
                'created_by' => $this->user->id,
                'updated_by' => null
            ]);

        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function projectWiseDataTypeAdd(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $validatedData = $request->validate([
                    'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                ]);
                extract($validatedData);
                $alreadyHas = Project_wise_data_type_required_info::where('company_id', $company_id)->where('pdri_id', $pdri_id)->pluck('data_type_id')->toArray();
                $dataTypes = VoucherType::where('company_id', $company_id)->whereNotIn('id', $alreadyHas)->where('status',1)->get(['id', 'voucher_type_title', 'code']);
                if ($dataTypes->isEmpty()) {
                    throw new \Exception('Nothing to add new');
                }
                $departments = department::where('company_id', $company_id)->where('status', 1)->get(['id', 'dept_code', 'dept_name']);
                if ($departments->isEmpty())
                {
                    throw new \Exception('Department not found for this company');
                }
                $view = view('back-end.requisition.__project_wise_data_type_add', compact('dataTypes','departments','pdri_id','project_id','company_id'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view],
                    'message' => 'Request processed successfully.'
                ]);
            }
            throw new \Exception('Request method not allowed!');
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function projectWiseDataTypeAddSubmit(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $validatedData = $request->validate([
                    'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                    'data_types' => ['required', 'array'],
                    'data_types.*' => ['required', 'string', 'exists:voucher_types,id',Rule::unique('project_wise_data_type_required_infos','data_type_id')->where(function ($query) use ($request) {
                        return $query->where('pdri_id', $request->post('pdri_id'))->where('company_id', $request->post('company_id'));
                    })],
                    'res_users' => ['required', 'array'],
                    'res_users.*' => ['required', 'string', 'exists:users,id'],
                    'deadline' => ['required', 'string', 'date'],
                ]);
                extract($validatedData);
                $userDataMap = []; // user_id => [ ['name' => ..., 'deadline' => ...], ... ]

                foreach ($data_types as $data_type_id) {
                    $pwdtr = Project_wise_data_type_required_info::firstOrCreate(
                        [
                            'pdri_id' => $pdri_id,
                            'data_type_id' => $data_type_id,
                        ],
                        [
                            'company_id' => $company_id,
                            'deadline' => $deadline,
                            'status' => 1,
                            'created_by' => $this->user->id,
                            'updated_by' => null,
                            'created_at' => now(),
                            'updated_at' => null,
                        ]
                    );

                    if ($pwdtr && $res_users) {
                        $this->responsibleUserEntry($res_users, $pwdtr, $company_id, $userDataMap);
                    }
                }
                if (!empty($userDataMap)) {
                    $pdri = Project_document_requisition_info::with('project')->find($pdri_id);
                    $projectName = optional($pdri->project)->branch_name ?? 'Unknown Project';

                    foreach ($userDataMap as $user_id => $dataTypes) {
                        $user = Cache::remember("user_{$user_id}", 60, fn () => User::find($user_id));
                        $user->notify(new DataArchiveProjectAssignedSummaryNotification(
                            $projectName,
                            $dataTypes,
                            company_info::find($validatedData['company_id'])->company_name,
                            route('add.archive.info'),
                        ));
                    }
                }
                $result = $this->projectWiseDataTypeReportDetailsData($pdri_id, $project_id, $company_id);
                $view = view('back-end.requisition._project_wise_data_type_wise_document_status_report_details', compact('result'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view],
                    'message' => 'Request processed successfully.'
                ]);
            }
            throw new \Exception('Request method not allowed!');
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function projectWiseDataTypeResponsibleUserAdd(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $validatedData = $request->validate([
                    'pwdtr_id' => ['required', 'string', 'exists:project_wise_data_type_required_infos,id'],
                    'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                ]);
                extract($validatedData);
                $data_type = Project_wise_data_type_required_info::with(['archiveDataType:id,voucher_type_title,code'])->find($pwdtr_id);
                $departments = department::where('company_id', $company_id)->where('status', 1)->get(['id', 'dept_code', 'dept_name']);
                if ($departments->isEmpty())
                {
                    throw new \Exception('Department not found for this company');
                }
                $existing_users = Required_data_type_upload_responsible_user_info::with([
                    'user:id,name,employee_id,dept_id',
                    'user.department:id,dept_code,dept_name',
                    ])
                    ->where('pwdtr_id', $pwdtr_id)
                    ->get();
                $view = view('back-end.requisition.__project_wise_data_type_responsible_user_add', compact('existing_users','pwdtr_id','departments','data_type','pdri_id','project_id','company_id'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view,],
                    'message' => 'Request processed successfully.'
                ]);
            }
            throw new \Exception('Request method not allowed!');
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function projectWiseDataTypeResponsibleUserSubmit(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $validatedData = $request->validate([
                    'pwdtr_id' => ['required', 'string', 'exists:project_wise_data_type_required_infos,id'],
                    'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                    'users' => ['required', 'array'],
                    'users.*' => ['required', 'string', 'exists:users,id'],
                ]);
                extract($validatedData);
                if ($pwdtr_id && $users)
                {
                    $pwdtr = Project_wise_data_type_required_info::with('archiveDataType', 'projectDocumentReq.project')->find($pwdtr_id);
                    $projectName = optional($pwdtr->projectDocumentReq->project)->branch_name ?? 'Unknown Project';
                    $this->responsibleUserEntry($users, $pwdtr, $company_id);
                    // Send notification to each user
                    foreach ($users as $user_id) {
                        $user = Cache::remember("user_{$user_id}", 60, fn () => User::find($user_id));

                        $user->notify(new DataArchiveProjectAssignedSummaryNotification(
                            $projectName,
                            [[
                                'name' => $pwdtr->archiveDataType->voucher_type_title,
                                'deadline' => $pwdtr->deadline,
                            ]],
                            company_info::find($validatedData['company_id'])->company_name,
                            route('add.archive.info'),
                        ));
                    }
                    $existing_users = Required_data_type_upload_responsible_user_info::with([
                        'user:id,name,employee_id,dept_id',
                        'user.department:id,dept_code,dept_name',
                    ])
                        ->where('pwdtr_id', $pwdtr_id)
                        ->get();
                    $view = view('back-end.requisition.___responsible_user_table', compact('existing_users'))->render();
                    $result = $this->projectWiseDataTypeReportDetailsData($pdri_id, $project_id, $company_id);
                    $view2 = view('back-end.requisition._project_wise_data_type_wise_document_status_report_details', compact('result'))->render();
                    return response()->json([
                        'status' => 'success',
                        'data' => ['view' => $view, 'view2' => $view2],
                        'message' => 'Request processed successfully.'
                    ]);
                }
            }
            throw new \Exception('Request method not allowed!');
        }
        catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

//    private function responsibleUserEntry($users, $pwdtr_id, $company_id)
//    {
//        $pwdtr = Project_wise_data_type_required_info::find($pwdtr_id);
//        $projectName = optional($pwdtr->projectDocumentReq->project)->branch_name ?? 'Unknown Project';
//        $deadline = $pwdtr->deadline;
//
//        $insertData = [];
//        $notifyUsers = [];
//
//        foreach ($users as $user_id) {
//            $exists = Required_data_type_upload_responsible_user_info::where('pwdtr_id', $pwdtr_id)
//                ->where('user_id', $user_id)
//                ->exists();
//
//            if (!$exists) {
//                $insertData[] = [
//                    'company_id' => $company_id,
//                    'pwdtr_id' => $pwdtr_id,
//                    'user_id' => $user_id,
//                    'created_by' => $this->user->id,
//                    'updated_by' => null,
//                    'created_at' => now(),
//                    'updated_at' => null,
//                ];
//
//                $notifyUsers[] = $user_id;
//            }
//        }
//
//        if (!empty($insertData)) {
//            DB::transaction(function () use ($insertData, $notifyUsers, $pwdtr_id, $projectName, $deadline) {
//                Required_data_type_upload_responsible_user_info::insert($insertData);
//                foreach ($notifyUsers as $user_id) {
//                    $user = User::find($user_id);
//                    if ($user) {
//                        $user->notify(new DataArchiveResponsibleUserAssigned($pwdtr_id, $projectName, $deadline));
//                    }
//                }
//            });
//        }
//    }

    private function responsibleUserEntry($users, $pwdtr, $company_id, &$userDataMap =[])
    {
        $insertData = [];
        foreach ($users as $user_id) {
            $exists = Required_data_type_upload_responsible_user_info::where('pwdtr_id', $pwdtr->id)
                ->where('user_id', $user_id)
                ->exists();

            if (!$exists) {
                $insertData[] = [
                    'company_id' => $company_id,
                    'pwdtr_id' => $pwdtr->id,
                    'user_id' => $user_id,
                    'created_by' => $this->user->id,
                    'updated_by' => null,
                    'created_at' => now(),
                    'updated_at' => null,
                ];

                // Aggregate data types for notification
                $userDataMap[$user_id][] = [
                    'id' => $pwdtr->id,
                    'name' => $pwdtr->archiveDataType->voucher_type_title,
                    'deadline' => $pwdtr->deadline,
                ];
            }
        }

        if (!empty($insertData)) {
            Required_data_type_upload_responsible_user_info::insert($insertData);
        }
    }


    public function projectWiseDataTypeResponsibleUserDelete(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $validatedData = $request->validate([
                    'res_users' => ['required', 'array'],
                    'res_users.*' => ['required', 'string', 'exists:required_data_type_upload_responsible_user_infos,id'],
                    'pdri_id' => ['required', 'string', 'exists:project_document_requisition_infos,id'],
                    'project_id' => ['required', 'string', 'exists:branches,id'],
                    'company_id' => ['required', 'string', 'exists:company_infos,id'],
                ]);
                extract($validatedData);
                $resUsers = $validatedData['res_users'];
                $responsibleBy = Required_data_type_upload_responsible_user_info::whereIn('id', $resUsers)->get();

                if ($responsibleBy->isEmpty()) {
                    throw new \Exception('No responsible users found to delete.');
                }

                $pwdtr_id = $responsibleBy->first()->pwdtr_id; // Get common pwdtr_id
                $old_responsibleBy = [];

                DB::beginTransaction();

                foreach ($responsibleBy as $responsible) {
                    $old_responsibleBy[] = [
                        'old_id' => $responsible->id,
                        'company_id' => $responsible->company_id,
                        'pwdtr_id' => $responsible->pwdtr_id,
                        'user_id' => $responsible->user_id,
                        'old_created_by' => $responsible->created_by,
                        'old_updated_by' => $responsible->updated_by,
                        'old_created_at' => $responsible->created_at,
                        'old_updated_at' => $responsible->updated_at,
                        'created_by' => $this->user->id,
                        'updated_by' => null,
                        'created_at' => now(),
                        'updated_at' => null,
                    ];
                    $responsible->delete();
                }

                if (!empty($old_responsibleBy)) {
                    Required_data_type_upload_responsible_user_infos_delete_history::insert($old_responsibleBy);
                }

                DB::commit();

                $existing_users = Required_data_type_upload_responsible_user_info::with([
                    'user:id,name,employee_id,dept_id',
                    'user.department:id,dept_code,dept_name',
                ])->where('pwdtr_id', $pwdtr_id)->get();

                $view = view('back-end.requisition.___responsible_user_table', compact('existing_users'))->render();
                $result = $this->projectWiseDataTypeReportDetailsData($pdri_id, $project_id, $company_id);
                $view2 = view('back-end.requisition._project_wise_data_type_wise_document_status_report_details', compact('result'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => ['view' => $view, 'view2' => $view2],
                    'message' => 'Responsible users deleted and logged successfully.',
                ]);
            }

            throw new \Exception('Request method not allowed!');
        } catch (\Throwable $exception) {
            DB::rollBack(); // Ensure rollback on error
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
