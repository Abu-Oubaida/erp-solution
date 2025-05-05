<?php

namespace App\Http\Controllers;

use App\Models\Project_document_requisition_info;
use App\Models\Project_wise_data_type_required_info;
use App\Models\Required_data_type_upload_responsible_user_info;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {

    }

    public function create(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->store($request);
            }
            $permission = $this->permissions()->project_document_requisition_entry;
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.requisition.add',compact('companies'))->render();
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
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

                        $insertData = [];

                        foreach ($users as $user_id) {
                            $exists = Required_data_type_upload_responsible_user_info::where('pwdtr_id', $pwdtr->id)
                                ->where('user_id', $user_id)
                                ->exists();

                            if (!$exists) {
                                $insertData[] = [
                                    'company_id' => $validatedData['company_id'],
                                    'pwdtr_id' => $pwdtr->id,
                                    'user_id' => $user_id,
                                    'created_by' => $this->user->id,
                                    'updated_by' => null,
                                    'created_at' => now(),
                                    'updated_at' => null,
                                ];
                            }
                        }

                        if (!empty($insertData)) {
                            Required_data_type_upload_responsible_user_info::insert($insertData);
                        }
                    }
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data added successfully.',
                ]);
            }
            throw new \Exception('Request method not allowed');
        }catch (\Exception $exception){
            return response()->json([
                'status' => 'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }
    public function companyWiseRequiredData(Request $request)
    {
        try {
            $permision = $this->permissions()->requisition;
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $projects = $this->getUserProjectPermissions($this->user->id,$permision)->without('getUsers','company')->where('status',1)->where('company_id',$company_id)->select('id','branch_name','address')->get();
                $types = $this->archiveTypeList($company_id)->without('voucherWithUsers','createdBY','updatedBY','company','archiveDocumentInfos','archiveDocuments')->where('status',1)->where('company_id',$company_id)->select('id','voucher_type_title','code')->get();
                $departments = $this->getDepartment($permision)->without('createdBy','updatedBy','getUsers','company')->where('company_id',$company_id)->where('status',1)->where('company_id',$company_id)->select('id','dept_code','dept_name')->get();
                return response()->json([
                    'status' => 'success',
                    'data' => ['projects'=>$projects,'types'=>$types,'departments'=>$departments],
                    'message' => 'Request processed successfully.'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message'=> 'Request method not allowed!',
            ]);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
