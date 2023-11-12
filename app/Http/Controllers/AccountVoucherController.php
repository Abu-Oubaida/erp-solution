<?php

namespace App\Http\Controllers;

use App\Exports\UsersSalaryCertificateDataExport;
use App\Models\Account_voucher;
use App\Models\User;
use App\Models\UserSalaryCertificateData;
use App\Models\VoucherDocument;
use App\Models\VoucherType;
use http\Env\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AccountVoucherController extends Controller
{
    //
    private $accounts_document_path = "file-manager/Account Document/";

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
    public function create(Request $request)
    {
        try {
            if ($request->isMethod('post'))
            {
                return $this->store($request);
            }
            $voucherTypes = VoucherType::where('status',1)->get();
            $user = Auth::user();
            $voucherInfos = Account_voucher::with(['VoucherDocument','VoucherType','createdBY','updatedBY'])->where('created_by',$user->id)->orWhere('updated_by',$user->id)->get();
//            dd($voucherInfos);
            return view('back-end/account-voucher/add',compact("voucherTypes","voucherInfos"));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage())->withInput();
        }
    }

    private function store(Request $request)
    {
        $request->validate([
            'voucher_number'    =>  ['required','string','unique:account_voucher_infos,voucher_number'],
            'voucher_date'      =>  ['required','date'],
            'voucher_type'      =>  ['required','numeric','exists:voucher_types,id'],
            'remarks'           =>  ['sometimes','nullable','string'],
            'voucher_file.*'    =>  ['required','max:512000'],
        ]);
        DB::beginTransaction();
        try {
            extract($request->post());
            $user = Auth::user();
//            dd(count($request->file('voucher_file')));
            $firstInsert = DB::table('account_voucher_infos')->insertGetId([
                'voucher_type_id'   =>  $voucher_type,
                'voucher_number'    =>  $voucher_number,
                'voucher_date'      =>  $voucher_date,
                'file_count'        =>  count($request->file('voucher_file')),
                'remarks'           =>  $remarks,
                'created_by'        =>  $user->id,
                'created_at'        =>  now(),
            ]);
            if (!$firstInsert) {
                // Rollback the transaction if the first insert failed
                DB::rollBack();
                return redirect()->back()->with('error', 'Failed to execute the first insert.');
            }
            if ($firstInsert && $request->hasFile('voucher_file')) {
                foreach ($request->file('voucher_file') as $file) {
                    // Handle each file
                    $fileName = $file->getClientOriginalName();
                    $file_location = $file->move($this->accounts_document_path,$fileName); // Adjust the storage path as needed
                    if (!$file_location)
                    {
                        return redirect()->back()->with('error', 'Data uploaded error.');
                    }

                    $secondInsert = DB::table('voucher_documents')->insert([
                        'voucher_info_id'   =>  $firstInsert,
                        'document'          =>  $fileName,
                        'filepath'          =>  $this->accounts_document_path,
                        'created_by'        =>  $user->id,
                        'created_at'        =>  now(),
                    ]);

                    if (!$secondInsert) {
                        // Rollback the transaction if the second insert for any item failed
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Failed to execute the second insert.');
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data inserts were successful.');

        }catch (\Throwable $exception)
        {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $exception->getMessage())->withInput();
        }

    }

    public function voucherList()
    {
        try {
            $voucherInfos = Account_voucher::with(['VoucherDocument','VoucherType','createdBY','updatedBY'])->get();
            return view('back-end/account-voucher/list',compact('voucherInfos'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
    }

    public function voucherDocumentView($vID)
    {
        try {
            $id = Crypt::decryptString($vID);
            $document = VoucherDocument::with(['accountVoucherInfo','accountVoucherInfo.VoucherType'])->find($id);
            return view('back-end/account-voucher/single-view',compact('document'));
        }catch (\Throwable $exception)
        {
            return back()->with('error',$exception->getMessage());
        }
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
                '*.1' => ['exists:users,name'],
                '*.3' => ['date'],
                '*.4' => ['date','after:*.3'],
                '*.5' => ['numeric'],
                '*.6' => ['numeric'],
                '*.7' => ['numeric'],
                '*.8' => ['numeric'],
                '*.9' => ['numeric'],
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
//                    $stroed[$key]= $key;
                    $user = User::where('employee_id',$data[0])->first();
                    if ($user)
                    {
                        $checkData = UserSalaryCertificateData::where('financial_yer_from',$data[3])->where('financial_yer_to',$data[4])->where('user_id',$user->id)->first();
                        if (!$checkData)
                        {
                            $insert = UserSalaryCertificateData::create([
                                'status'=>1,
                                'user_id'=>$user->id,
                                'financial_yer_from'=>$data[3],//From year
                                'financial_yer_to'=>$data[4],//To year
                                'basic'=>$data[5],//Basic
                                'house_rent'=>$data[6],//House Rent
                                'conveyance'=>$data[7],//Conveyance
                                'medical_allowance'=>$data[8],//Medical allowance
                                'festival_bonus'=>$data[9],//Festival Bonus
                                'others'=>$data[10],//Others
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
            $checkData = UserSalaryCertificateData::where('financial_yer_from',$from)->where('financial_yer_to',$to)->where('user_id',$user->id)->first();
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

        $id = Crypt::decryptString($id);
        $data = UserSalaryCertificateData::with('userInfo')->where('status',1)->where('id',$id)->first();
        return view('back-end.account-voucher.salary.input-certificate-view',compact('data'));
    }
}
