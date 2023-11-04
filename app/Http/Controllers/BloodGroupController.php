<?php

namespace App\Http\Controllers;

use App\Models\BloodGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BloodGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        //
        $bloods = BloodGroup::all();
        return view('back-end.blood-group.list',compact('bloods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create(Request $request)
    {
        //
        try {
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }
            $bloods = BloodGroup::all();
            return view('back-end.blood-group.add',compact('bloods'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        //
        try {
            $request->validate([
                'blood_type'    => ['required','string','unique:blood_groups,blood_type']
            ]);
            extract($request->post());
            BloodGroup::create([
                'blood_type'    =>  $blood_type,
                'created_by'    =>  Auth::user()->id,
            ]);
            return back()->with('success','Data added successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BloodGroup  $bloodGroup
     * @return Response
     */
    public function show(BloodGroup $bloodGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BloodGroup  $bloodGroup
     * @return Response
     */
    public function edit(BloodGroup $bloodGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BloodGroup  $bloodGroup
     * @return Response
     */
    public function update(Request $request, BloodGroup $bloodGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Request $request)
    {
        try {
            extract($request->post());
            BloodGroup::where('id',Crypt::decryptString($id))->delete();
            return back()->with('success','Data delete successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
}
