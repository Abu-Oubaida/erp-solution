<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        try {
            $permission = $this->permissions()->project_document_requisition_entry;
            $companies = $this->getCompanyModulePermissionWise($permission)->get();
            return view('back-end.requisition.add',compact('companies'))->render();
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
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
