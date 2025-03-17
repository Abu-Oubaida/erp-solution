@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">Edit data type</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">Edit data type</h3>
                        </div>
                        <form action="{!! route('edit.archive.type',['archiveTypeID'=>\Illuminate\Support\Facades\Request::route('archiveTypeID')]) !!}" method="POST">
                            @csrf
                            {!! method_field('put') !!}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company">
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="data_type_title" name="data_type_title" type="text" placeholder="Enter Voucher Type Title" value="{!!$voucherType->voucher_type_title !!}" required/>
                                        <label for="data_type_title">Voucher Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="data_type_code" name="data_type_code" type="number" placeholder="Enter Voucher Type Code" value="{!! $voucherType->code !!}" required/>
                                        <label for="data_type_code">Voucher Type Code</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if($voucherType->status == '1') selected @endif>Active</option>
                                            <option value="0" @if($voucherType->status == '0') selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="remarks" name="remarks"> {!! $voucherType->remarks !!}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3 float-end">
                                        <input onclick="return confirm('Are you sure!')" type="submit" value="Update" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include("back-end.archive.type._list")
        </div>

    </div>
@stop

