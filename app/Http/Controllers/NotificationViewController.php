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
    public function index(Request $request)
    {
        try {
            $notification_data = null;
            if ($request->get('n'))
            {
                $notification_data = auth()->user()->notifications()->where('id', $request->get('n'))->first();
                $notification_data->markAsRead();
            }
            $notifications = auth()->user()->notifications();
            if ($request->get('status'))
            {
                if ($request->get('status') == 'unread')
                    $notifications = auth()->user()->unreadNotifications();
                elseif ($request->get('status') == 'read')
                    $notifications = auth()->user()->readNotifications();
                else{
                    $notifications = auth()->user()->notifications();
                }
            }
            $perPage = request('per_page', 10); // Default to 10

            if ($perPage === 'all') {
                $notifications = $notifications
                    ->orderByRaw('ISNULL(read_at) DESC') // Unread first
                    ->orderBy('created_at', 'desc')      // Most recent first
                    ->get(); // No pagination
            } else {
                $notifications = $notifications
                    ->orderByRaw('ISNULL(read_at) DESC') // Unread first
                    ->orderBy('created_at', 'desc')
                    ->paginate((int) $perPage)
                    ->appends(request()->except('page')); // Preserve query
            }

            return view('back-end.notification', compact('notifications','notification_data'))->render();
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function notificationReadUnread(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post'))
            {
                $validate = $request->validate([
                    'notification_ids' => ['required','array'],
                    'notification_ids.*' => ['required','string','exists:notifications,id'],
                    'status' => ['required','in:0,1'],
                ]);
                extract($validate);
                foreach ($notification_ids as $notification_id)
                    auth()->user()->notifications()->where('id',$notification_id)->update(['read_at' => $status == 1 ? now() : null]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification status updated successfully',
                ]);
            }
            throw new \Exception('Request method not allowed');
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function notificationDelete(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post'))
            {
                $validate = $request->validate([
                    'notification_ids' => ['required','array'],
                    'notification_ids.*' => ['required','string','exists:notifications,id'],
                ]);
                extract($validate);
                foreach ($notification_ids as $notification_id)
                    auth()->user()->notifications()->where('id',$notification_id)->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification deleted successfully',
                ]);
            }
            throw new \Exception('Request method not allowed');
        }catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
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
