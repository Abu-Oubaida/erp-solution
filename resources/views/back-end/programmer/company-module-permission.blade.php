@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('company.list')}}" class="text-capitalize text-chl">Company List</a>
        </li>
        <li class="breadcrumb-item"><a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h3 class="text-capitalize"><i class="fa-solid fa-city"></i> Company Info</h3>
                </div>
                <div class="col">
                    <a href="{!! route('user.company.permission',['companyID'=> \Illuminate\Support\Facades\Crypt::encryptString($company->id)]) !!}" class="btn btn-sm btn-outline-success float-end mt-2"> <i class="fas fa-shield"></i> User Permission</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Company Code</th>
                                <th>Company Type</th>
                                <th>Contract Person</th>
                                <th>Contract Number</th>
                                <th>Email</th>
                                <th>Location</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{!! $company->company_name !!}</td>
                                <td>{!! $company->company_code !!}</td>
                                <td>{!! $company->companyType->company_type_title !!}</td>
                                <td>{!! $company->contract_person_name !!}</td>
                                <td>{!! $company->phone !!}</td>
                                <td>{!! $company->email !!}</td>
                                <td>{!! $company->location !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <form action="{!! route('company.module.permission',['companyID'=>\Illuminate\Support\Facades\Crypt::encryptString($company->id)]) !!}" method="post">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-md-3">
                                <label for="permission_parent">Permission Parent</label>
                                <select class="form-control cursor-pointer select-search text-capitalize" name="permission_parent" id="permission_parent" onchange="Obj.companyModulePermission(this)">
                                    <option value="">--Select Option--</option>
                                    <option value="0">@ All</option>
                                    @if(isset($parent_permissions) && count($parent_permissions))
                                        @foreach($parent_permissions as $pp)
                                            <option value="{{$pp->id}}">{!! $pp->name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-7">
                                <label for="permission_parent">Permissions</label>
                                <select class="form-control cursor-pointer select-search" name="permissions[]" id="permissions" multiple onchange="Obj.selectAllOption(this)">
                                    <option value="">--Select Options--</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-chl-outline btn-sm mt-4" type="submit"> <i class="fas fa-plus"></i> Add Permission</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="text-capitalize"><i class="fa-solid fa-shield-halved"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                </div>
                <div class="col-sm-6">
                    <a href="" class="btn btn-outline-danger btn-sm float-end mt-1" ref="{!! $company->id !!}" onclick="return Obj.deleteCompanyModulePermissionAll(this)"><i class="fas fa-trash"></i> Delete All</a>
                </div>
            </div>
        </div>
        <div class="card-body" id="company-permission-list">
            @include('back-end.programmer.__company-module-permission-list')
        </div>
    </div>

</div>
@stop

