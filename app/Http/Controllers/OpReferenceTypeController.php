<?php

namespace App\Http\Controllers;

use App\Models\Op_reference_type;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class OpReferenceTypeController extends Controller
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
            if ($request->isMethod('post'))
            {
                $this->store($request);
            }
            $op_ref_types = $this->getOperationReferenceType()->get();
            $companies = $this->getCompany()->get();
            return view('back-end.programmer.operation_ref_type',compact('op_ref_types','companies'));
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
    private function store(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $request->validate([
                    'company' => ['required','string','max:255','exists:company_infos,id'],
                    'name' => ['required','string','max:255',Rule::unique('op_reference_types','name')->where(function ($query) use($request){
                        $query->where('company_id',$request->get('company'));
                    })],
                    'code' => ['required','string','max:255',Rule::unique('op_reference_types','code')->where(function ($query) use($request){
                        $query->where('company_id',$request->get('company'));
                    })],
                    'description' => ['sometimes','nullable','string'],
                    'status' => ['numeric','required','between:0,1'],
                ]);
                extract($request->post());
                Op_reference_type::create([
                    'company_id' => $company,
                    'name' => $name,
                    'code' => $code,
                    'description' => $description,
                    'status' => $status,
                    'created_by' => $this->user->id,
                    'created_at' => now(),
                ]);
                return back()->with('success', 'Data added successful');
            }
            return back()->with('error','Requested methode not allowed');
        }catch (Throwable $exception){
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function edit(Request $request,$id)
    {
        try {
            $id = Crypt::decryptString($id);
            if ($request->isMethod('put'))
            {
                $this->update($request,$id);
            }
            $op_ref_types = $this->getOperationReferenceType()->get();
            $type = $this->getOperationReferenceType()->where('id',$id)->first();
            return view('back-end.programmer.operation_ref_type_edit',compact('op_ref_types','type'));
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage())->withInput();
        }

    }
    public function update(Request $request,$id)
    {
        try {
            $request->validate([
                'company' => ['required','string','max:255','exists:company_infos,id'],
                'name' => ['required','string','max:255',Rule::unique('op_reference_types','name')->where(function ($query) use ($request) {
                    $query->where('company_id',$request->get('company'));
                })->ignore($id)],
                'code' => ['required','string','max:255',Rule::unique('op_reference_types','code')->where(function ($query) use ($request) {
                    $query->where('company_id',$request->get('company'));
                })->ignore($id)],
                'description' => ['sometimes','nullable','string'],
                'status' => ['numeric','required','between:0,1'],
            ]);
            extract($request->post());
            Op_reference_type::where('id',$id)->update([
                'name' => $name,
                'code' => $code,
                'description' => $description,
                'status' => $status,
                'updated_by' => $this->user->id,
                'updated_at' => now(),
            ]);
            return back()->with('success', 'Data updated successful');
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'id'    =>  ['string','required'],
            ]);
            extract($request->post());
            $id = Crypt::decryptString($id);
            $data = Op_reference_type::with(['fixedAssetOpening'])->where('id',$id)->first();
            if(count($data->fixedAssetOpening))
            {
                return back()->with('error', 'There exist some relation with fixed asset opening');
            }
            if ($data)
            {
                DB::table('op_reference_types_delete_histories')->insert([
                    'old_id' => $data->id,
                    'company_id' => $data->company_id,
                    'name' => $data->name,
                    'code' => $data->code,
                    'description' => $data->description,
                    'status' => $data->status,
                    'created_by' => $data->created_by,
                    'updated_by' => $data->updated_by,
                    'deleted_by' => $this->user->id,
                    'old_created_at'=> $data->created_at,
                    'old_updated_at'=> $data->updated_at,
                    'created_at' => now(),
                ]);
                $data->delete();
                return back()->with('success', 'Data deleted successful');
            }
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
}
