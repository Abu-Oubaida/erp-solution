<?php

namespace App\Http\Controllers;

use App\Models\complains;
use App\Models\department;
use App\Models\priority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'priority'    =>  ['required', 'numeric'],
            'to'    =>  ['required', 'numeric'],
            'content'    =>  ['required', 'string'],
        ]);
        extract($request->post());
        try {
            if (!($pri = priority::where('status',1)->where('id',$priority)->first()))
            {
                return back()->with('error','Invalid Priority Selection!')->withInput();
            }
            if (!($dept = department::where('status',1)->where('id',$to)->first()))
            {
                return back()->with('error','Invalid Department Selection!')->withInput();
            }
            $user = Auth::user();
            complains::create([
                'user_id'       =>  $user->id,
                'title'         =>  $complain_title,
                'priority'      =>  $pri->priority_number,
                'details'       =>  "$content",
                'to_dept'       =>  $dept->id,
                'status'        =>  1
            ]);
            return back()->with('success','Data save successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
        dd($content);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\complains  $complains
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $user = Auth::user();
            $complains = null;
            if ($user->roles->first()->name == 'user')
            {
                $complains = complains::leftJoin('users as u','u.id','complains.user_id')->where('user_id','!=',$user->id)->where('forward_to',$user->id)->get();
            }
            return view('back-end/complain/list',compact('complains'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }


    }
    public function myList()
    {
        $user = Auth::user();
        if ($user->roles->first()->name == 'user')
        {
            $complains = complains::where('user_id',$user->id)->get();
            dd($complains);
        }

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
