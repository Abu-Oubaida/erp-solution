<?php

namespace App\Http\Controllers\systemsuperadmin;

use App\Http\Controllers\Controller;
use App\Models\Data_archive_storage_package;
use App\Models\Data_archive_storage_package_delete_history;
use App\Traits\ParentTraitCompanyWise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DataArchivePackageController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
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


    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            if ($request->ajax())
            {
                $validate = $request->validate([
                    'package_name' => ['required','string','max:255','unique:data_archive_storage_packages,package_name'],
                    'package_size' => ['required','numeric'],
                    'package_status' => ['required','in:0,1'],
                ]);
                extract($validate);
                Data_archive_storage_package::create([
                    'package_name' => $package_name,
                    'package_size' => $package_size,
                    'status' => $package_status,
                    'created_by' => $this->user->id,
                    'created_at' => now(),
                    'updated_by' => null,
                    'updated_at' => null,
                ]);
                $storage_packages = $this->getStoragePackages()->get();
                $view = view('back-end.archive-package._list',compact('storage_packages'))->render();
                return response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Data added successfully',
                ]);
            }
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        try {
            if ($request->ajax())
            {
                $validate = $request->validate([
                    'edit_id' => ['required','integer','exists:data_archive_storage_packages,id'],
                ]);
                extract($validate);
                $data = $this->getStoragePackages()->where('id',$edit_id)->first();
                if (!$data)
                {
                    throw new \Exception('Data not found');
                }
                $view = view('back-end.archive-package._edit',compact('data'))->render();
                return response()->json([
                    'status'=>'success',
                    'data'=>$view,
                ]);
            }
            throw new \Exception('Not implemented yet');
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            if ($request->ajax())
            {
                $validate = $request->validate([
                    'package_id' => ['required','integer','exists:data_archive_storage_packages,id'],
                    'package_name' => ['required','string', Rule::unique('data_archive_storage_packages','package_name')->ignore($request->post('package_id'))],
                    'package_size' => ['required','numeric','max:255'],
                    'package_status' => ['required','in:0,1'],
                ]);
                extract($validate);
                Data_archive_storage_package::where('id',$package_id)->update([
                    'package_name' => $package_name,
                    'package_size' => $package_size,
                    'status' => $package_status,
                    'updated_by' => $this->user->id,
                    'updated_at' => now(),
                ]);
                $storage_packages = $this->getStoragePackages()->get();
                $view = view('back-end.archive-package._list',compact('storage_packages'))->render();
                return response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Data updated successfully',
                ]);
            }
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        try {
            if ($request->ajax())
            {
                $validate = $request->validate([
                    'delete_id' => ['required','integer','exists:data_archive_storage_packages,id'],
                ]);
                extract($validate);
                $data = $this->getStoragePackages()->where('id',$delete_id)->first();
                if (!$data)
                    throw new \Exception('Data not found');
                if ($data->usees_company_count)
                    throw new \Exception('Data is used by company');
                Data_archive_storage_package_delete_history::create([
                    'old_id' => $data->id,
                    'status' => $data->status,
                    'package_name' => $data->package_name,
                    'package_size' => $data->package_size,
                    'package_type' => $data->package_type,
                    'old_created_by' => $data->created_by,
                    'old_created_at' => $data->created_at,
                    'old_updated_by' => $data->updated_by,
                    'old_updated_at' => $data->updated_at,
                    'created_by' => $this->user->id,
                    'updated_by' => null,
                ]);
                $data->delete();
                $storage_packages = $this->getStoragePackages()->get();
                $view = view('back-end.archive-package._list',compact('storage_packages'))->render();
                return response()->json([
                    'status'=>'success',
                    'data'=>$view,
                    'message'=>'Data deleted successfully',
                ]);
            }
            throw new \Exception('Not implemented yet');
        }catch (\Throwable $exception)
        {
            return response()->json([
                'status'=>'error',
                'message'=>$exception->getMessage()
            ]);
        }
    }
}
