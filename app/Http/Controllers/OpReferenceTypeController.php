<?php

namespace App\Http\Controllers;

use App\Models\Op_reference_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class OpReferenceTypeController extends Controller
{
    private $user;
    private $company_id;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $this->company_id = $this->user->company_id;
        });
    }
    //
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $this->store($request);
            }
            $op_ref_types = Op_reference_type::with(['createdBy','updatedBy'])->get();
            return view('back-end.programmer.operation_ref_type',compact('op_ref_types'));
        }catch (Throwable $exception){
            return back()->with('error', $exception->getMessage())->withInput();
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
}
