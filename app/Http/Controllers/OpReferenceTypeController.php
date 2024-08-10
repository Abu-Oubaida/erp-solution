<?php

namespace App\Http\Controllers;

use App\Models\Op_reference_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Throwable;

class OpReferenceTypeController extends Controller
{
    private $user;
    private $company_id;
    public function __construct(Request $request)
    {
//        dd($this->setAuthUser());
        $this->middleware(function ($request, $next) {
            $this->user= Auth::user();
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
            $op_ref_types = Op_reference_type::with(['createdBy','updatedBy'])->get();
            return view('back-end.programmer.operation_ref_type',compact('op_ref_types'));
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
                    'name' => ['required','string','max:255','unique:op_reference_types,name'],
                    'code' => ['required','string','max:255','unique:op_reference_types,code'],
                    'description' => ['sometimes','nullable','string'],
                    'status' => ['numeric','required','between:0,1'],
                ]);
                extract($request->post());
                Op_reference_type::create([
                    'name' => $name,
                    'code' => $code,
                    'description' => $description,
                    'status' => $status,
                    'company_id' => $this->company_id,
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
            $op_ref_types = Op_reference_type::with(['createdBy','updatedBy'])->get();
            $type = Op_reference_type::where('id',$id)->first();
            return view('back-end.programmer.operation_ref_type_edit',compact('op_ref_types','type'));
        }catch (\Throwable $exception){
            return back()->with('error', $exception->getMessage())->withInput();
        }

    }
    public function update(Request $request,$id)
    {
        try {
            $request->validate([
                'name' => ['required','string','max:255',Rule::unique('op_reference_types','name')->ignore($id)],
                'code' => ['required','string','max:255',Rule::unique('op_reference_types','code')->ignore($id)],
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
}
