<?php

namespace App\Http\Controllers;

use App\Models\complains;
use App\Models\department;
use App\Models\priority;
use Illuminate\Http\Request;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->isMethod('post'))
        {
            return $this->store($request);
        }
        $priorities = priority::where('status',1)->get();
        $depts = department::where('status',1)->get();
        return view('back-end.complain.add',compact('priorities','depts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function store(Request $request)
    {
        $request->validate([
            'complain_title'    =>  ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\complains  $complains
     * @return \Illuminate\Http\Response
     */
    public function show(complains $complains)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\complains  $complains
     * @return \Illuminate\Http\Response
     */
    public function edit(complains $complains)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\complains  $complains
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, complains $complains)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\complains  $complains
     * @return \Illuminate\Http\Response
     */
    public function destroy(complains $complains)
    {
        //
    }
}
