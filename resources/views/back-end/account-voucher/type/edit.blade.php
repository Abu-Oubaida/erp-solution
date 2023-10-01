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
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="{!! route('edit.voucher.type',['voucherTypeID'=>\Illuminate\Support\Facades\Request::route('voucherTypeID')]) !!}" method="POST">
                            @csrf
                            {!! method_field('put') !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="voucher_type_title" name="voucher_type_title" type="text" placeholder="Enter Voucher Type Title" value="{!!$voucherType->voucher_type_title !!}" required/>
                                        <label for="voucher_type_title">Voucher Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="voucher_type_code" name="voucher_type_code" type="number" placeholder="Enter Voucher Type Code" value="{!! $voucherType->code !!}" required/>
                                        <label for="voucher_type_code">Voucher Type Code</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if($voucherType->status == '1') selected @endif>Active</option>
                                            <option value="0" @if($voucherType->status == '0') selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="remarks" name="remarks"> {!! $voucherType->remarks !!}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input onclick="return confirm('Are you sure!')" type="submit" value="Update" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include("back-end.account-voucher.type._list")
        </div>

    </div>
@stop

