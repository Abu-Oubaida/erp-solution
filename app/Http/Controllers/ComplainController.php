<?php

namespace App\Http\Controllers;

use App\Models\complains;
use App\Models\department;
use App\Models\priority;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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
    }

    /**
     * Display the specified resource.
     *
     * @param complains $complains
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {
            $user = Auth::user();
            $complains = null;
//            if ($user->roles->first()->name == 'user')
//            {
                $complains = complains::leftJoin('users as u','u.id','complains.user_id')->where('user_id','!=',$user->id)->where('forward_to',$user->id)->select('complains.*','u.name')->get();
//            }
            return view('back-end/complain/list',compact('complains'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }


    }
    public function singleView($complainID)
    {
        try {
            $user = Auth::user();
            $userRole = $user->roles()->first();
            $forwardTo = null;
            if (isset($userRole) && $userRole->id <= 3)
            {
                $forwardTo = User::where('status',1)->where('id','!=',$user->id)->get();
            }
            else{
                $forwardTo = User::where('status',1)->where('dept_id',$user->dept_id)->get();
            }
            $comID = Crypt::decryptString($complainID);
            $com = complains::leftJoin('users as u','u.id','complains.user_id')->where('complains.id',$comID)->select('complains.*','u.name')->first();
//            dd($com);
            return view('back-end.complain.single-view',compact('com','forwardTo','complainID'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function myList()
    {
        try {
            $user = Auth::user();
//            if ($user->roles->first()->name == 'user')
//            {
                $complains = complains::leftJoin('users as u','u.id','complains.forward_to')->leftJoin('departments as dept','dept.id','to_dept')->where('complains.user_id',$user->id)->where('complains.status','<','6')->get(['u.name','complains.*']);
                return view('back-end/complain/my-list',compact('complains'));
//            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function deptList()
    {
        try {
            $user = Auth::user();
//            if ($user->roles->first()->id == 'user')
//            {
                $complains = complains::leftJoin('users as u','u.id','complains.forward_to')->leftJoin('departments as dept','dept.id','to_dept')->leftJoin('users as uu','uu.id','complains.user_id')->where('complains.to_dept',$user->dept_id)->where('complains.status','<','6')->get(['u.name','uu.name as submittedBy','complains.*']);
                return view('back-end/complain/departmental-list',compact('complains'));
//            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function myTrashList()
    {
        try {
            $user = Auth::user();
//            if ($user->roles->first()->name == 'user')
//            {
                $complains = complains::leftJoin('users as u','u.id','complains.forward_to')->leftJoin('departments as dept','dept.id','to_dept')->where('complains.user_id',$user->id)->where('complains.status','=','7')->get(['u.name','complains.*']);
//                dd($complains);
                return view('back-end/complain/my-trash-list',compact('complains'));
//            }
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function editMy(Request $request, $id)
    {
        $complain = null;
        try {
            if ($request->isMethod('post'))
            {
                return $this->editMyStore($request);
            }
            $uese = Auth::user();
            $cid = Crypt::decryptString($id);
            $complain = complains::leftJoin('users as u','u.id','complains.user_id')->where('complains.status',1)->where('complains.user_id',$uese->id)->where('complains.id',$cid)->select('complains.*','u.name')->first();
            if ($complain == null)
            {
                return back()->with('error','Edit not possible!');
            }
            $priorities = priority::where('status',1)->get();
            $depts = department::where('status',1)->get();
            return view('back-end.complain.edit',compact('complain','priorities','depts','id'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }

    }
    public function editMyStore(Request $request)
    {
        $request->validate([
            'complain_title'    =>  ['required', 'string', 'max:255'],
            'priority'    =>  ['required', 'numeric'],
            'to'    =>  ['required', 'numeric'],
            'status'    =>  ['required', 'numeric'],
            'ref'    =>  ['required', 'numeric'],
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
            if (!($status == 1 || $status == 7))
            {
                $status = 7;
            }
            complains::where('id',$ref)->where('user_id',$user->id)->update([
                'updated_by'       =>  $user->id,
                'title'         =>  $complain_title,
                'priority'      =>  $pri->priority_number,
                'details'       =>  "$content",
                'to_dept'       =>  $dept->id,
                'status'        =>  $status,
                'updated_at'    =>  date('Y-m-d H:i:s'),
            ]);
            return back()->with('success','Data update successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param complains $complains
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
     * @param complains $complains
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, complains $complains)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy( $id): RedirectResponse
    {
        try {
            $user = Auth::user();
            $cid = Crypt::decryptString($id);
            complains::where('id',$cid)->where('user_id',$user->id)->update([
//                'updated_by'    =>  $user->id,
                'status'        =>  7,// where 7 = inactive but delete for user
                'updated_at'    =>  date('Y-m-d H:i:s'),
            ]);
            return back()->with('success','Data delete successfully');
        }catch (\Throwable $exception)
        {
            return back();
        }
    }
}
