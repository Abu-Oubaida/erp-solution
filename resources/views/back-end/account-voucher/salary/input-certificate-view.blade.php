@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <div class="col-md-5 col-sm-6">
                        @if(auth()->user()->hasPermission('salary_certificate_list'))
                            <a class="btn btn-success btn-sm" href="{{route("salary.certificate.list")}}"><i class="fas fa-list-check"></i> List View</a>
                        @endif
                    </div>
                    <div class="col-md-7 col-sm-6">
                        @if(auth()->user()->hasPermission('salary_certificate_input'))
                            <a class="btn btn-success btn-sm" href="{{route("input.salary.certificate")}}"><i class="fas fa-upload" aria-hidden="true"></i> Input Option</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                    </div>
                    @if($data)
{{--                        <div class="col">--}}
{{--                            <a href="{!! route('salary.certificate.download',['salaryInfoID'=>\Illuminate\Support\Facades\Crypt::encryptString($data->id)]) !!}" class="btn btn-sm btn-outline-success float-end" target="_blank"> <i class="fas fa-download"></i> Download</a>--}}
{{--                        </div>--}}
                        <div class="col">
                            <a href="{!! route('salary.certificate.preview',['salaryInfoID'=>\Illuminate\Support\Facades\Crypt::encryptString($data->id)]) !!}" class="btn btn-sm btn-outline-info float-end" target="_blank"> <i class="fas fa-print"></i> Print</a>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="" class="table">
                            <thead>
                            <tr class="table-secondary">
                                <th>Employee ID</th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Department</th>
                                <th>Branch</th>
                                <th><span title="Financial Year From">F.Y. From</span></th>
                                <th><span title="Financial Year To">F.Y. To</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($data))
                                <tr class="table-info">
                                    <td>{!! $data->userInfo->employee_id !!}</td>
                                    <td>@if($data->userInfo->status == 1) <span class="badge bg-success">Active</span>@elseif($data->userInfo->status == 0) <span class="badge bg-danger">Inactive</span>@else <span class="badge bg-warning">Unknown</span> @endif</td>
                                    <td>{!! $data->userInfo->name !!}</td>
                                    <td>{!! $data->userInfo->getDesignation->title !!}</td>
                                    <td>{!! $data->userInfo->getDepartment->dept_name !!}</td>
                                    <td>{!! $data->userInfo->getBranch->branch_name !!}</td>
                                    <td>{!! date('F-Y', strtotime($data->financial_yer_from)) !!}</td>
                                    <td>{!! date('F-Y', strtotime($data->financial_yer_to)) !!}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="15" class="text-danger text-center">Not Found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="table-secondary">
                                    <th>Key</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Basic</th>
                                    <td>Tk. {!! $data->basic !!}/=</td>
                                </tr>
                                <tr>
                                    <th>House Rent</th>
                                    <td>Tk. {!! $data->house_rent !!}/=</td>
                                </tr>
                                <tr>
                                    <th>Conveyance</th>
                                    <td>Tk. {!! $data->conveyance !!}/=</td>
                                </tr>
                                <tr>
                                    <th>Medical Allowance</th>
                                    <td>Tk. {!! $data->medical_allowance !!}/=</td>
                                </tr>
                                <tr>
                                    <th>Festival Bonus</th>
                                    <td>Tk. {!! $data->festival_bonus !!}/=</td>
                                </tr>
                                <tr>
                                    <th>Others</th>
                                    <td>Tk. {!! $data->others !!}/=</td>
                                </tr>
                                <tr class="table-danger">
                                    <th>Total</th>
                                    <th>Tk. {!! ($total = $data->basic + $data->house_rent + $data->conveyance + $data->medical_allowance + $data->festival_bonus + $data->others) !!}/=
                                        <br> ({!! amountToWords(($total),'Taka','Paisa','BDT') !!} Only)</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <form action="{!! route('transaction.submit') !!}" method="post">
                            @csrf
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="dated" name="dated" type="date" placeholder="Dated" value="{{old('dated')}}"/>
                                    <label for="dated">Dated <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <input type="hidden" name="user_salary_certificate_data_id" value="{!! $data->id !!}">
                            <div class="col-md-2">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="amount" name="amount" type="number" placeholder="Amount" value="{{old('amount')}}"/>
                                    <label for="amount">Amount <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="challan_no" name="challan_no" type="text" placeholder="Challan number" value="{{old('challan_no')}}"/>
                                    <label for="challan_no">Challan number <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-floating mb-3">
                                    <select class="form-control" name="type" id="type">
                                        <option value="bank" {!! (old('type') == 'bank')?'selected':'' !!}>Bank</option>
                                        <option value="cash" {!! (old('type') == 'cash')?'selected':'' !!}>Cash</option>
                                    </select>
                                    <label for="type">Type <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name">
                                    <label for="bank_name">Bank Name </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-chl-outline float-end"><i class="fas fa-plus"></i> Add</button>
                            </div>
                        </div>
                        </form>

                        <h3>List of Transaction</h3>
                        <table class="table table-bordered">
                            <thead>
                            <tr class="table-secondary">
                                <th>SL</th>
                                <th>Dated</th>
                                <th>Challan No.</th>
                                <th>Amount</th>
                                <th><div class="text-center">Action</div></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($totalT = 0)
                            @php($sl = 1)
                            @if($transactions && count($transactions))
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{!! $sl++ !!}</td>
                                    <td>{!! $transaction->dated !!}</td>
                                    <td>{!! $transaction->challan_no !!}</td>
                                    <td>Tk. {!! $totalT += $transaction->amount !!}/=</td>
                                    <td><div class="text-center"><i class="fas fa-trash text-danger"></i></div></td>
                                </tr>
                                @endforeach
                            @endif
                            <tr class="table-danger">
                                <th colspan="3">Total</th>
                                <th colspan="2">Tk. {!! $totalT !!}/= <br>({!! amountToWords(($totalT),'Taka','Paisa','BDT') !!})</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

