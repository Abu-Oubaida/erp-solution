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
                        <a style="text-decoration: none;" href="#" class="text-capitalize">Add data type</a>
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
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="text-capitalize"><i class="fas fa-plus"></i> Add new data type</h3>
                            </div>
                        @if(auth()->user()->haspermission('archive_data_type_list'))
                            <div class="col-4">
                                <a class="btn btn-primary btn-sm float-end mt-1" href="{!! route('archive.data.type.list') !!}"><i class="fa-solid fa-list"></i> Data Type List</a>
                            </div>
                        @endif
                        </div>
                    </div>
                    <div class="card-body">
                      <form action="{!! route('add.archive.type') !!}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseDepartments(this,'company_wise_departments')">
                                            <option value="">--select option--</option>
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
                                        <input class="form-control" id="data_type_title" name="data_type_title" type="text" placeholder="Enter Voucher Type Title" value="{!! old('data_type_title') !!}" required/>
                                        <label for="data_type_title">Data Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="data_type_code" name="data_type_code" type="number" placeholder="Enter Voucher Type Code" value="{{old('data_type_code')}}" required/>
                                        <label for="data_type_code">Data Type Code</label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if(old('status') == '1') selected @endif>Active</option>
                                            <option value="0" @if(old('status') == '0') selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="remarks" name="remarks"> {!! old('remarks') !!}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                @if(auth()->user()->hasPermission('add_archive_data_type_user_permission'))
                                <div class="col-md-5 mb-1">
                                    <label for="department">Enter Department Name</label>
                                    <select id="company_wise_departments" multiple name="department" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)">
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex float-start align-items-center">
                                    <a class="btn btn-sm btn-outline-secondary mt-3" id="search-icon" onclick="return Obj.searchCompanyDepartmentUsers('company','company_wise_departments','company_departments_users')"><i class="fas fa-search"></i> Find Users</a>
                                </div>
                                <div class="col-md-5 mb-1">
                                    <label for="department">User List</label>
                                    <select id="company_departments_users" name="company_departments_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                @else
                                <div class="col-md-12">
                                @endif
                                    <div class="form-floating mb-3 float-end">
                                        <button type="submit" class="btn btn-chl-outline mt-4" name="submit" ><i class="fas fa-save"></i> Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

