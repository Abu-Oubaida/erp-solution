<?php

namespace App\Http\Controllers;

use App\Models\branch;
use App\Models\Document_requisition_attested_document_info;
use App\Models\Document_requisition_info;
use App\Models\Document_requisition_receiver_user;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function indexDocument()
    {
        try {
            dd('view document');
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function createDocumentRequisition(Request $request)
    {
        try {
            $permission = $this->permissions()->add_document_requisition;
            if ($request->isMethod('post'))
            {
                return $this->storeDocumentRequisition($request);
            }else{
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                $documents = $this->documentRequisition($permission)->where('created_by',Auth::id())->get();
                $depts = $this->getDepartment($permission)->where('company_id',$this->user->company_id)->where('status',1)->get();
                return view('back-end.requisition.add',compact('depts','companies','documents'))->render();
            }
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function storeDocumentRequisition(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $validatedData = $request->validate([
                    'company' => ['required','string','exists:company_infos,id'],
                    'users' => ['required','array',],
                    'users.*' => ['required','string','exists:users,id'],
                    'deadline' => ['required','date'],
                    'd_count' => ['sometimes','nullable','integer','min:1'],
                    'subject' => ['required','string'],
                    'details' => ['sometimes','nullable','string'],
                ]);
                extract($validatedData);
                $today_data_count = Document_requisition_info::whereDate('created_at', date('Y-m-d'))->count();
                $reference = date("dmy") . '-' . ($today_data_count + 1);

                while (Document_requisition_info::where('reference_number', $reference)->exists()) {
                    $today_data_count++;
                    $reference = date("dmy") . '-' . $today_data_count;
                }
//                DB::beginTransaction(); // Start Transaction
                DB::transaction(function () use ($request, $reference, $company, $deadline, $d_count, $subject, $details, $users){
                    $documentInfo = Document_requisition_info::create([
                        'reference_number' => $reference,
                        'status' => 1, // 1 = active
                        'sander_company_id' => Auth::user()->company_id,
                        'receiver_company_id' => $company,
                        'deadline' => $deadline,
                        'number_of_document' => $d_count ?? 0,
                        'subject' => $subject,
                        'description' => $details ?? null,
                        'created_by' => $this->user->id,
                    ]);

                    if ($documentInfo) {
                        if (!empty($d_count)) {
                            for ($counter = 1; $counter <= $d_count; $counter++) {
                                $variable = "d_title_" . $counter;
                                $value = $request->post($variable);

                                Document_requisition_attested_document_info::create([
                                    'document_requisition_id' => $documentInfo->id,
                                    'document_title' => $value,
                                    'document_upload_status' => 0, // 0 = not uploaded yet
                                    'created_by' => $this->user->id,
                                ]);
                            }
                        }

                        foreach ($users as $user) {
                            Document_requisition_receiver_user::create([
                                'document_requisition_id' => $documentInfo->id,
                                'user_id' => $user,
                                'reply_status' => 0,
                                'created_by' => $this->user->id,
                            ]);
                        }
                    }
                });
                return back()->with('success', 'Document requisition created successfully!');
            }
            return back()->with('error','Requested method not allowed!')->withInput();
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document_requisition_info  $document_requisition_info
     * @return \Illuminate\Http\Response
     */
    public function show(Document_requisition_info $document_requisition_info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document_requisition_info  $document_requisition_info
     * @return \Illuminate\Http\Response
     */
    public function edit(Document_requisition_info $document_requisition_info)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document_requisition_info  $document_requisition_info
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Document_requisition_info $document_requisition_info)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document_requisition_info  $document_requisition_info
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document_requisition_info $document_requisition_info)
    {
        //
    }

    public function reqDocumentReceiver(Request $request)
    {
        try {
            $permission = $this->permissions()->requisition;
            if ($request->isMethod('post'))
            {
                $validatedData = $request->validate([
                    'id' => ['required','string','exists:document_requisition_infos,id'],
                ]);
                extract($validatedData);
                $data = $this->documentRequisition($permission)->find($id)->receivers;
                $view = view('back-end.requisition.__receiver-list-modal',compact('data'))->render();
                return response()->json([
                    'status' => 'success',
                    'view' => $view,
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Request method not allowed!'
            ]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }
    public function requestedDocument(Request $request)
    {
        try {
            $permission = $this->permissions()->requisition;
            if ($request->isMethod('post'))
            {
                $validatedData = $request->validate([
                    'id' => ['required','string','exists:document_requisition_infos,id'],
                ]);
                extract($validatedData);
                $data = $this->documentRequisition($permission)->find($id)->attachmentInfos;
                $view = view('back-end.requisition.__requested-document-modal',compact('data'))->render();
                return response()->json([
                    'status' => 'success',
                    'view' => $view,
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Request method not allowed!'
            ]);
        }catch (\Throwable $exception){
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function documentRequisitionReceivedList()
    {
        try {
            $permission = $this->permissions()->document_requisition_received_list;
            $documents = $this->documentRequisition($permission)->whereHas('receiverUser',function ($query){
                $query->where('user_id', $this->user->id);
            })->get();
            $view = view('back-end.requisition.list',compact('documents'))->render();
            return $view;
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }
    public function documentRequisitionSentList()
    {
        try {
            $permission = $this->permissions()->document_requisition_sent_list;
            $documents = $this->documentRequisition($permission)->where('created_by',Auth::id())->get();
            $view = view('back-end.requisition.list',compact('documents'))->render();
            return $view;
        }catch (\Throwable $exception){
            return back()->with('error',$exception->getMessage());
        }
    }
}
