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
        @if(auth()->user()->hasPermission('add_archive_data_type_user_permission'))
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize"><i class="fas fa-key"></i> Data Type Permission</h3>
                            </div>
                            <div class="col">
                            @if(auth()->user()->hasPermission('archive_data_type_list'))
                                <a class="btn btn-primary btn-sm float-end mt-1" href="{!! route('archive.data.type.list') !!}"><i class="fa-solid fa-list"></i> Data Type List</a>
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-1">
                                <div class="">
                                    <label for="company">Company Name (Type) <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjectsAndDataTypeArchive(this,null,'data_types',true)">
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
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-1">
                                    <label for="company2">Company Name (Users) <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company2" name="company" onchange="return Obj.companyWiseDepartments(this,'company_wise_departments')">
                                        <option value="">--Please select a option--</option>
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mb-1">
                                <label for="department">Department Name (Users) <span class="text-danger">*</span></label>
                                <select id="company_wise_departments" multiple name="department" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)">
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex float-start align-items-center">
                                <a class="btn btn-sm btn-outline-secondary mt-2" id="search-icon" onclick="return Obj.searchCompanyDepartmentUsers('company2','company_wise_departments','permission_users')"><i class="fas fa-search"></i> Find Users</a>
                            </div>
                            <div class="col-md-3 mb-1">
                                <label for="department">User List <span class="text-danger">*</span></label>
                                <select id="permission_users" name="company_departments_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-chl-outline mt-1 float-end" onclick="return Archive.settingSetTypePermission(this,'company','data_types','permission_users')"><i class="fas fa-save"></i> Save Permission</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasPermission('archive_company_storage_management'))
        <div class="row">

        </div>
        @endif
    </div>
@stop

