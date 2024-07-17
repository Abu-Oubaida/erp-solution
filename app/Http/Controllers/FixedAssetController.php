<?php

namespace App\Http\Controllers;

use App\Models\Fixed_asset;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
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
    public function create()
    {
        dd('add fixed asset here');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return \Illuminate\Http\Response
     */
    public function show(Fixed_asset $fixed_asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return \Illuminate\Http\Response
     */
    public function edit(Fixed_asset $fixed_asset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fixed_asset $fixed_asset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fixed_asset  $fixed_asset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fixed_asset $fixed_asset)
    {
        //
    }
}
