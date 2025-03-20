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
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="company">Company Name <span class="text-danger">*</span></label>
                            <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseDepartments(this,'company_wise_departments')">
                                <option value="">--select option--</option>
                                @if(isset($companies) || (count($companies) > 0))
                                    @foreach($companies as $c)
                                        <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-1">
                        <div class="">
                            <label for="data_type">Type<span class="text-danger">*</span></label>
                            <select class="form-control text-capitalize select-search" name="data_type" id="data_type">
                                <option value="">--Select a Type--</option>
{{--                                @foreach($voucherTypes as $date)--}}
{{--                                    <option value="{!! $date->id !!}">{!! $date->voucher_type_title !!}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
