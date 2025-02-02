<?php

namespace App\Http\Controllers\superadmin;

use App\Exports\PermissionInputDataPrototypeExport;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\User;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class prorammerController extends Controller
{
    //
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                $this->store($request);
            }
            $permissions = Permission::with(['childPermission','parentName'])->where('parent_id',null)->orWhere('is_parent','!=',null)->orderBy('id','ASC')->get();
//            dd($permissions);
            return view('back-end.programmer.permission-input',compact("permissions"));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    protected function store(Request $request)
    {
        try {
            $rules= [
                'permission_parent'  => ['string','required', 'max:255','exists:permissions,id'],
                'permission_name'  => ['string','required', 'max:255','regex:/^[a-z0-9_]+$/','unique:permissions,name'],
                'permission_display_name'  => ['string','required', 'max:255','unique:permissions,display_name'],
                'description'  => ['string','sometimes','nullable', 'max:255'],
            ];
            $customMessages = [
                'custom_separator' => 'Without underscore "_" all separator are invalid!'
            ];
            $this->validate($request, $rules, $customMessages);
            extract($request->post());
            if (Permission::where('id',$permission_parent)->where('name','none')->first())
            {
                $permission_parent = null;
            }
            Permission::create([
                'parent_id' =>  $permission_parent,
                'name'      =>  $permission_name,
                'is_parent'  =>  $request->is_parent,
                'display_name'=>  $permission_display_name,
                'description'=>  $description,
            ]);
            $permissions = Permission::with(['childPermission','parentName'])->where('parent_id',null)->orWhere('is_parent','!=',null)->orderBy('id','ASC')->get();
            $view   = view('back-end.programmer._permission-list',compact("permissions"))->render();
            return response()->json([
                'status' => 'success',
                'data' => $view,
                'parents' => $permissions,
                'message' => 'Request processed successfully.',
            ], 200);
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 200);
        }
    }

    public function permissionStoreBulk(Request $request)
    {
        try {
            $input = $request->post()['input'];
            unset($input[0]);
            $rules= [
                '*.0'  => ['string','required', 'max:255'],//permission_parent
                '*.2'  => ['string','required', 'max:255','regex:/^[a-z0-9_]+$/'],//permission_name
                '*.3'  => ['string','required', 'max:255'],//display_name
                '*.4'  => ['string','sometimes','nullable', 'max:255'],//description
            ];
            $customMessages = [
                'custom_separator' => 'Without underscore "_" all separator are invalid!'
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                // Return an error response in JSON format
                $errors = $validator->errors();
                $response = [
                    'error' => true,
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ];
            }
            else {
                $insert = 0;
                $notInsert = 0;
                foreach ($input as $key=>$data)
                {
                    $parent = Permission::where('name',$data[0])->first();
                    if (!Permission::where('name',$data[2])->first())
                    {
                        Permission::create([
                            'parent_id' =>  (@$parent->id ?? NULL),
                            'is_parent'  =>  (isset($data[1]) && ($data[1] == 'Parent' || $data[1] == 'parent'))?1:NULL,
                            'name'  =>  $data[2],
                            'display_name'=>  $data[3],
                            'description'=>  $data[4],
                        ]);
                        $insert++;
                    }
                    else
                    {
                        $notInsert++;
                    }
                }
                $response = [
                    'error' => false,
                    'message' => "Inserted $insert data successfully. Not inserted $notInsert data.",
                    'errors' => null,
                ];
            }
            return response()->json($response, 200);
        }catch (\Throwable $exception)
        {
            $response = [
                'error' => true,
                'code' => $exception->getCode(), // You can use any appropriate error code
                'message' => $exception->getMessage(),
            ];
            return response()->json($response, 200);
        }
    }
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id'    =>  ['string','required'],
            ]);
            extract($request->post());
            $dataID = Crypt::decryptString($id);
            $permission = Permission::find($dataID);
//            dd(PermissionUser::where('parent_id',$dataID)->orWhere('permission_name',$permission->name)->first());
            if (PermissionUser::where('parent_id',$dataID)->orWhere('permission_name',$permission->name)->first())
            {
                return back()->with('warning','This data has relation between another table. Data delete not possible!');
            }
            Permission::where('id',$dataID)->delete();
            Permission::where('parent_id',$dataID)->delete();
            return back()->with('success','Data delete successfully!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function exportPrototype(Request $request)
    {
        return Excel::download(new PermissionInputDataPrototypeExport(),'permission-input-data-prototype.xlsx');
    }
}
