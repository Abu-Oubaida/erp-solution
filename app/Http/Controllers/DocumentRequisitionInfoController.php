<?php

namespace App\Http\Controllers;

use App\Models\Document_requisition_info;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;

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
    public function companyWiseUser(Request $request)
    {
        try {
            $permision = $this->permissions()->requisition;
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company_id' => ['required','string','exists:company_infos,id'],
                ]);
                extract($request->post());
                $users = $this->companyWisePermissionUsers($company_id,$permision)->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $users,
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
    public function createDocument(Request $request)
    {
        try {
            $permission = $this->permissions()->add_document_requisition;
            if ($request->isMethod('post'))
            {
                return $this->storeDocument($request);
            }else{
                $companies = $this->getCompanyModulePermissionWise($permission)->get();
                $depts = $this->getDepartment($permission)->where('company_id',$this->user->company_id)->where('status',1)->get();
                return view('back-end.requisition.add',compact('depts','companies'))->render();
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
    public function storeDocument(Request $request)
    {
        //
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
}
