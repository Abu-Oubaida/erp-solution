@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
{{--        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">Data type list</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            @include("back-end.archive.type._list")
        </div>

    </div>

    <!-- Modal for details -->
    @if(auth()->user()->hasPermission('add_archive_data_type_user_permission'))
    <div class="modal fade" id="permissionEditModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="permissionEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionEditModalLabel"><i class="fas fa-key"></i> Data Type Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="permissionEditModalContent">
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-8 mb-1">
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="department">Department Name (Users) <span class="text-danger">*</span></label>
                                    <select id="company_wise_departments" multiple name="department" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)">
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-2 float-start">
                                    <button class="btn btn-sm btn-outline-secondary mt-4" id="search-icon" onclick="return Obj.searchCompanyDepartmentUsers('company2','company_wise_departments','permission_users')"><i class="fas fa-search"></i> Find Users</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-1">
                            <label for="department">User List <span class="text-danger">*</span></label>
                            <select id="permission_users" name="company_departments_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                <option value="">Pick options...</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-1">
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
                        <div class="col-md-4 mb-1">
                            <div class="">
                                <label for="data_type">Type<span class="text-danger">*</span></label>
                                <select class="form-control text-capitalize select-search" name="data_type" id="data_types" multiple onchange="Obj.selectAllOption(this)">
                                    <option value="">--Select a Type--</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-chl-outline mt-4 float-end" onclick="return Archive.settingSetTypePermission(this,'company','data_types','permission_users')"><i class="fas fa-save"></i> Save Permission</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop

