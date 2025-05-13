<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index()
    {
        try {
            $perPage = request('per_page', 10); // Default to 10

            if ($perPage === 'all') {
                $notifications = auth()->user()
                    ->notifications()
                    ->orderBy('created_at', 'desc')
                    ->get(); // No pagination
            } else {
                $notifications = auth()->user()
                    ->notifications()
                    ->orderBy('created_at', 'desc')
                    ->paginate((int) $perPage)
                    ->appends(request()->except('page')); // Preserve query
            }

            return view('back-end.notification', compact('notifications'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
