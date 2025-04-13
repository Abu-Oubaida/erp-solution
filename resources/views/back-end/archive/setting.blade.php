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
                        <a style="text-decoration: none;" href="#" class="text-capitalize">Data Archive Settings</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize"><i class="fas fa-key"></i> Data Type Permission</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-2 mb-1">
                                    <div class="">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjectsAndDataTypeArchive(this,'project','data_types',true)">
                                            <option value="">Pick options...</option>
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <div class="">
                                        <label for="data_type">Type<span class="text-danger">*</span></label>
                                        <select class="form-control text-capitalize select-search" name="data_type" id="data_types" multiple onchange="Obj.selectAllOption(this)">
                                            <option value="">--Select a Type--</option>
                                            {{--                                    @foreach($voucherTypes as $date)--}}
                                            {{--                                        <option value="{!! $date->id !!}">{!! $date->voucher_type_title !!}</option>--}}
                                            {{--                                    @endforeach--}}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <label for="project">Enter Branch Name</label>
                                    <select id="project" name="project" class="select-search cursor-pointer" multiple onchange="Obj.selectAllOption(this)">
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-sm btn-outline-secondary mt-4"><i class="fas fa-search"></i> Search User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

