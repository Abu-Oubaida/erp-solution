<?php

namespace App\Http\Controllers;

use App\Models\Account_voucher;
use App\Models\VoucherType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AccountVoucherController extends Controller
{
    //
    public function createVoucherType(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->storeVoucherType($request);
            }
            $voucherTypes = $this->voucherTypeList();
            return view('back-end/account-voucher/type/add',compact('voucherTypes'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    private function storeVoucherType(Request $request):RedirectResponse
    {
        $request->validate([
            'voucher_type_title'   =>  ['required', 'string', 'max:255'],
            'voucher_type_code'    =>  ['sometimes','nullable', 'numeric'],
            'status'               =>  ['required', 'numeric'],
            'remarks'              =>  ['sometimes','nullable', 'string'],
        ]);
        extract($request->post());
        try {
            if (VoucherType::where('voucher_type_title',$voucher_type_title)->orWhere('code',$voucher_type_code)->first())
            {
                return back()->with('error','Duplicate data found!')->withInput();
            }
            $user = Auth::user();
            VoucherType::create([
                'status'    =>  $status,
                'voucher_type_title'=>  $voucher_type_title,
                'code'      =>  $voucher_type_code,
                'remarks'   =>  $remarks,
                'created_by'=>  $user->id,
                'updated_by'=>  $user->id,
            ]);
            return back()->with('success','Data insert successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function editVoucherType(Request $request, $voucherTypeID)
    {
        try {
            if ($request->isMethod('put'))
            {
                return $this->updateVoucherType($request,$voucherTypeID);
            }
            $vtID = Crypt::decryptString($voucherTypeID);
            $voucherType = VoucherType::find($vtID);
            $voucherTypes = $this->voucherTypeList();
            return view('back-end/account-voucher/type/edit',compact('voucherType','voucherTypes'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function voucherTypeList()
    {
        return VoucherType::get();
    }
    private function updateVoucherType(Request $request,$voucherTypeID)
    {
        $request->validate([
            'voucher_type_title'   =>  ['required', 'string', 'max:255'],
            'voucher_type_code'    =>  ['sometimes','nullable', 'numeric'],
            'status'               =>  ['required', 'numeric'],
            'remarks'              =>  ['sometimes','nullable', 'string'],
        ]);
        extract($request->post());
        try {
            $vtID = Crypt::decryptString($voucherTypeID);
            if (!VoucherType::find($vtID))
            {
                return back()->with('error','Data not found!')->withInput();
            }
            if (VoucherType::where('id','!=',$vtID)->where('voucher_type_title',$voucher_type_title)->first() || VoucherType::where('id','!=',$vtID)->where('code',$voucher_type_code)->first())
            {
                return back()->with('error','Duplicate data found!')->withInput();
            }
            $user = Auth::user();
            VoucherType::where('id',$vtID)->update([
                'status'    =>  $status,
                'voucher_type_title'=>  $voucher_type_title,
                'code'      =>  $voucher_type_code,
                'remarks'   =>  $remarks,
                'updated_by'=>  $user->id,
            ]);
            return back()->with('success','Data update successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    public function deleteVoucherType(Request $request)
    {
        $request->validate([
            'id'   =>  ['required', 'string'  ],
        ]);
        try {
            extract($request->post());
            $vtID = Crypt::decryptString($id);
            $av = VoucherType::with(['accountVoucher'])->find($vtID);
            if($av->accountVoucher != null)
            {
                return back()->with('error','A relationship exists between other tables. Data delete not possible');
            }
            VoucherType::where('id',$vtID)->delete();

            return redirect(route('add.voucher.type'))->with('success','Data delete successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function create()
    {
        $voucherTypes = VoucherType::where('status',1)->get();
        return view('back-end/account-voucher/add',compact("voucherTypes"));
    }
}
