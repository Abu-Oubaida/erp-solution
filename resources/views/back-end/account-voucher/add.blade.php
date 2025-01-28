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
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                    </div>
                    <div class="col">
                        <div class="float-end">
                            @if(auth()->user()->hasPermission('list_voucher_document'))
                                <a class="btn btn-success btn-sm" href="{{route("uploaded.voucher.list")}}"><i class="fas fa-list-check"></i> Uploaded List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('add.voucher.info') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                            <div class="form-floating mb-2">
                                <input class="form-control" id="voucher_number" name="voucher_number" type="text" placeholder="Enter Voucher Number" value="{{old('voucher_number')}}"/>
                                <label for="voucher_number">Voucher Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-2">
                                <input class="form-control" id="voucher_date" name="voucher_date" type="date" placeholder="Enter Voucher Date" value="{{old('voucher_date')}}"/>
                                <label for="voucher_date">Voucher Date <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-2">
                                <select class="form-control" name="voucher_type" id="voucher_type">
                                    <option value="">--Select Voucher Type--</option>
                                    @foreach($voucherTypes as $date)
                                        <option value="{!! $date->id !!}">{!! $date->voucher_type_title !!}</option>
                                    @endforeach
                                </select>
                                <label for="voucher_type">Voucher Type<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <input type="file" class="form-control" name="voucher_file[]" multiple id="voucher_file" >
                                <label for="voucher_file">Voucher Document<span class="text-danger">*</span></label>
                                <small>jpeg,png,pdf/ Maximum size 500 MB</small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10"></textarea>
                                <label for="remarks">Remarks</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating mb-3 float-end">
                                <input type="submit" value="Upload" class="btn btn-chl-outline" name="submit" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">Your Added/Updated Documents List</h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('back-end.account-voucher._voucher_list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

