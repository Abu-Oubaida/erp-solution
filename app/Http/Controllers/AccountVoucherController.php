<?php

namespace App\Http\Controllers;

use App\Exports\UsersSalaryCertificateDataExport;
use App\Models\Account_voucher;
use App\Models\SalaryCertificateTransection;
use App\Models\User;
use App\Models\UserSalaryCertificateData;
use App\Models\VoucherDocument;
use App\Models\VoucherDocumentDeleteHistory;
use App\Models\VoucherDocumentIndividualDeletedHistory;
use App\Models\VoucherDocumentShareEmailLink;
use App\Models\VoucherType;

use App\Rules\AccountVoucherInfoStatusRule;
use App\Traits\ParentTraitCompanyWise;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AccountVoucherController extends Controller
{
    use ParentTraitCompanyWise;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->setUser();
            return $next($request);
        });
    }
    public function salaryCertificateInput(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->salaryCertificateInputStore($request);
            }
            $users = User::with(['getDepartment','getBranch'])->where('status',1)->get();
            $datas = UserSalaryCertificateData::with('userInfo','userInfo.getDepartment','userInfo.getBranch')->where('status',1)->where(function ($query){
                $query->where('created_by',Auth::user()->id);
                $query->orWhere('updated_by',Auth::user()->id);
            })->get();
//            dd($datas);
            return view('back-end/account-voucher/salary/input-certificate',compact('users','datas'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function salaryCertificateInputExcelStore(Request $request)
    {
        try {
            $input = $request->post()['input'];
            unset($input[0]);
            // Define validation rules for the request data
            $rules = [
                '*.*' => ['required'],
                '*.0' => ['exists:users,employee_id'],
                '*.1' => ['string'],
                '*.3' => ['date'],
                '*.4' => ['date','after:*.3'],
                '*.5' => ['numeric'],
                '*.6' => ['numeric'],
                '*.7' => ['numeric'],
                '*.8' => ['numeric'],
                '*.9' => ['numeric'],
                '*.10' => ['numeric'],
                '*.11' => ['numeric'],
                '*.12' => ['string'],
            ];
            $customMessages = [
                '*.0.exists' => 'The :attribute with the Employee ID ":value" does not exist in the users table.',
                '*.1.exists' => 'The :attribute with the name ":value" does not exist in the users table.',
                '*.3.date' => 'Invalid date! Please check your excel file and make sure Financial Year From values data type must be string/text',
                '*.3.before' => 'Financial Year From must be before Financial Year To',
                '*.4.date' => 'Invalid date! Please check your excel file and make sure Financial Year To values data type must be string/text',
                '*.3.after' => 'Financial Year To must be after Financial Year From',
            ];
            $validator = Validator::make($input,$rules,$customMessages);
            // Check if validation fails
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
                // Return a success response in JSON format
                $unStroed=[];
                $alreadyHave=[];
                $stroed=[];
                foreach ($input as $key=>$data)
                {
                    $financial_yer_from = $data[3];
                    $financial_yer_to = $data[4];
//                    $stroed[$key]= $key;
                    $user = User::where('employee_id',$data[0])->first();
                    if ($user)
                    {
                        $checkData = UserSalaryCertificateData::where('financial_yer_from',$financial_yer_from)->where('financial_yer_to',$financial_yer_to)->where('user_id',$user->id)->first();
                        if (!$checkData)
                        {
                            $insert = UserSalaryCertificateData::create([
                                'status'=>1,
                                'user_id'=>$user->id,
                                'financial_yer_from'=>$financial_yer_from,//From year
                                'financial_yer_to'=>$financial_yer_to,//To year
                                'basic'=>$data[5],//Basic
                                'house_rent'=>$data[6],//House Rent
                                'conveyance'=>$data[7],//Conveyance
                                'medical_allowance'=>$data[8],//Medical allowance
                                'festival_bonus'=>$data[10],//Festival Bonus
                                'others'=>$data[11],//Others
                                'remarks'=>$data[12],//Remarks
                                'created_by'=>Auth::user()->id,
                                'updated_by'=>null
                            ]);
                            if ($insert)
                            {
                                $stroed[$key] = [
                                    'Employee ID'  =>  $data[0],
                                    'Name'  =>  $data[1],
                                    'Department'  =>  $data[2],
                                ];
                            }
                            else{
                                $unStroed[$key] = [
                                    'Employee ID'  =>  $data[0],
                                    'Name'  =>  $data[1],
                                    'Department'  =>  $data[2],
                                ];
                            }
                        }
                        else{
                            $alreadyHave[$key] = [
                                'Employee ID'  =>  $data[0],
                                'Name'  =>  $data[1],
                                'Department'  =>  $data[2],
                            ];
                        }
                    }else{
                        $unStroed[$key] = [
                            'Employee ID'  =>  $data[0],
                            'Name'  =>  $data[1],
                            'Department'  =>  $data[2],
                        ];
                    }
                }
                $response = [
                    'error' => false,
                    'errorMessage' => $unStroed? $unStroed:null,
                    'successMessage' => $stroed? $stroed:null,
                    'alreadyHasMessage' => $alreadyHave? $alreadyHave:null,
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
            return response()->json($response, 422);
        }

    }
    private function salaryCertificateInputStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'officer' => ['required','string','exists:users,name'],
            'from' => ['required','date','before:to'],
            'to' => ['required','date','after:from'],
            'basic' => ['required','numeric'],
            'house_rent' => ['required','numeric'],
            'conveyance' => ['required','numeric'],
            'medical' => ['required','numeric'],
            'bonus' => ['required','numeric'],
            'others' => ['sometimes','nullable','numeric'],
            'remarks' => ['sometimes','nullable','string'],
        ]);
        if ($validator->fails()) {
//            return response()->json(['error' => $validator->errors()], 422);
            return back()->with('error',$validator->errors())->withInput();
        }
        try {
            extract($validator->getData());
            $user = User::where('name',$officer)->first();
            $fromDate = date('M-Y',strtotime($from));
            $toDate = date('M-Y',strtotime($to));
            $checkData = UserSalaryCertificateData::where('financial_yer_from',$fromDate)->where('financial_yer_to',$toDate)->where('user_id',$user->id)->first();
            if ($checkData)
            {
                return back()->with('warning',"This financial years data already has in DB, Can't add new, Please try to edit")->withInput();
            }
            UserSalaryCertificateData::create([
                'status'=>1,'user_id'=>$user->id,'financial_yer_from'=>$from,'financial_yer_to'=>$to,'basic'=>$basic,'house_rent'=>$house_rent,'conveyance'=>$conveyance,'medical_allowance'=>$medical,'festival_bonus'=>$bonus,'others'=>$others,'remarks'=>$remarks,'created_by'=>Auth::user()->id,'updated_by'=>null,
            ]);
            return back()->with('success','Data save successfully');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }
    public function salaryCertificateList()
    {
        try {
            $datas = UserSalaryCertificateData::with('userInfo','userInfo.getDepartment','userInfo.getBranch')->get();
            return view('back-end/account-voucher/salary/input-certificate-list',compact('datas'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function exportUserSalaryPrototype()
    {
        return Excel::download(new UsersSalaryCertificateDataExport,'salary certificate input data.xlsx');
    }
    public function salaryCertificateView($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $data = UserSalaryCertificateData::with('userInfo')->where('status',1)->where('id',$id)->first();
            $transactions = SalaryCertificateTransection::where('user_salary_certificate_data_id',$id)->get();
            return view('back-end.account-voucher.salary.input-certificate-view',compact('data','transactions'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function salaryCertificatePrint($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $data = UserSalaryCertificateData::with('userInfo')->where('status',1)->where('id',$id)->first();
            if (!$data) {
                abort(404); // or handle the not found case as needed
            }
            $transactions = SalaryCertificateTransection::where('user_salary_certificate_data_id',$id)->get();
            $pdf = Pdf::loadView('back-end/account-voucher/salary/input-certificate-print', compact('data','transactions'));
            return $pdf->download("salary_certificate_for_{$data->userInfo->name}.pdf");
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function previewPdf($id)
    {
        try {
            $id = Crypt::decryptString($id);
            $data = UserSalaryCertificateData::with('userInfo')->where('status',1)->where('id',$id)->first();
            if (!$data) {
                abort(404); // or handle the not found case as needed
            }
            $transactions = SalaryCertificateTransection::where('user_salary_certificate_data_id',$id)->get();
            $pdf = Pdf::loadView('back-end/account-voucher/salary/input-certificate-print', compact('data','transactions'));
            return $pdf->stream("preview_salary_certificate_for_{$data->userInfo->name}.pdf");
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }
    public function transactionSubmit(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_salary_certificate_data_id' =>  ['required','numeric','exists:user_salary_certificate_data,id'],
                'dated' =>  ['required','date'],
                'amount' =>  ['required','numeric'],
                'challan_no' =>  ['required','string','unique:salary_certificate_transections,challan_no'],
                'type' =>  ['sometimes','nullable','string'],
                'bank_name' =>  ['sometimes','nullable','string'],
            ]);
            $validated['created_by']=Auth::user()->id;
            extract($request->post());
            SalaryCertificateTransection::create($validated);
            return back()->with('success','Data add successfully!');
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }


}
