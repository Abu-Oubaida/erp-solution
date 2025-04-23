@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
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
            @if(auth()->user()->hasPermission('edit_archive_data_type'))
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="text-capitalize"><i class="fas fa-edit"></i> Edit data type</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{!! route('edit.archive.type',['archiveTypeID'=>\Illuminate\Support\Facades\Request::route('archiveTypeID')]) !!}" method="POST">
                            @csrf
                            {!! method_field('put') !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company">
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if($voucherType->company_id == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="data_type_title" name="data_type_title" type="text" placeholder="Enter Archive Type Title" value="{!!$voucherType->voucher_type_title !!}" required/>
                                        <label for="data_type_title">Archive Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="data_type_code" name="data_type_code" type="number" placeholder="Enter Archive Type Code" value="{!! $voucherType->code !!}" required/>
                                        <label for="data_type_code">Archive Type Code</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if($voucherType->status == '1') selected @endif>Active</option>
                                            <option value="0" @if($voucherType->status == '0') selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-12">
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
            @endif
            @if(auth()->user()->hasPermission('add_archive_data_type_user_permission'))
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="text-capitalize"><i class="fas fa-user-edit"></i> Data type user permission</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="company2">Company Name <span class="text-danger">*</span></label>
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
                            <input type="hidden" id="type_company" value="{!! $voucherType->company_id !!}">
                            <input type="hidden" id="type_id" value="{!! $voucherType->id !!}">
                            <div class="col-md-6 mb-1">
                                <label for="department">Enter Department Name</label>
                                <select id="company_wise_departments" multiple name="department" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)">
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex float-start align-items-center">
                                <a class="btn btn-sm btn-outline-secondary mt-2" id="search-icon" onclick="return Obj.searchCompanyDepartmentUsers('company2','company_wise_departments','permission_users')"><i class="fas fa-search"></i> Find Users</a>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="department">User List</label>
                                <select id="permission_users" name="company_departments_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating mb-3 float-end">
                                    <input onclick="return Archive.settingSetTypePermission(this,'type_company','type_id','permission_users')" type="submit" value="User Update" class="btn btn-chl-outline" name="submit" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if(auth()->user()->hasPermission('archive_data_type_list'))
            @include("back-end.archive.type._list")
            @endif
        </div>

    </div>
@stop

