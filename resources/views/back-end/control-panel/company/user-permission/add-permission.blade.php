@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('company.list')}}" class="text-capitalize text-chl text-decoration-none">Company List</a>
        </li>
        <li class="breadcrumb-item"><a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-3">
        <div class="card-header">
            <h3><i class="fa-solid fa-city"></i> Selected Company Information</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Sort Name</th>
                    <th>Status</th>
                    <th>Contact Person</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{!! $company->company_name !!}</td>
                    <td>{!! $company->company_code !!}</td>
                    <td>@if($company->status == 1) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
                    <td>{!! $company->contact_person_name !!}</td>
                    <td>{!! $company->contact_person_phone !!}</td>
                    <td>{!! $company->location !!}</td>
                </tr>
                </tbody>
            </table>
            <div class="col-md-12">
                <form action="{!! route('user.company.permission',['companyID'=>\Illuminate\Support\Facades\Crypt::encryptString($company->id)]) !!}" method="post">
                    @csrf
                    @method('post')
                    <div class="row">
                        <div class="col-sm-3 mb-1">
                            <label for="user">Select Role<span class="text-danger">*</span></label>
                            <select id="role" name="role" class="select-search cursor-pointer">
                                <option value="">Pick options...</option>
                                @if(count($roles))
                                    @foreach($roles as $r)
                                        <option value="{!! $r->id !!}">{!! $r->display_name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-7 mb-1">
                            <label for="user">Select Users Name<span class="text-danger">*</span></label>
                            <select id="user" name="users[]" class="select-search cursor-pointer" multiple>
                                <option value="">Pick options...</option>
                                @if(count($users))
                                    @foreach($users as $user)
                                        <option value="{!! $user->id !!}">{!! $user->name !!} (ID: {!! $user->employee_id !!})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-chl-outline" type="submit" id="ref-src-btn">
                                <i class="fa fa-user-plus"></i> Add To Company
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3> <i class="fa-solid fa-user-lock"></i> Permission Users </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <table class="table" id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>System ID</th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>User Status</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($company->permissionUsers))
                                @php($n=1)
                                @foreach($company->permissionUsers as $u)
                                    <tr>
                                        <td>{!! $n++ !!}</td>
                                        <td>{!! $u->id !!}</td>
                                        <td>{!! $u->name !!}</td>
                                        <td>{!! $u->employee_id !!}</td>
                                        <td>@if($u->status == 1) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
                                        <td>{!! isset($u->userRole)?$u->userRole->display_name:'' !!}</td>
                                        <td>
                                        @if(auth()->user()->hasPermission('delete_user_company_permission'))
                                            <form action="{{route('delete.user.company.permission')}}" class="display-inline" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($u->id) !!}">
                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">Not Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h3> <i class="fa-solid fa-user-lock"></i> Default Users </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <table class="table" id="datatablesSimple2">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>System ID</th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>User Status</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($company->users))
                                @php($n=1)
                                @foreach($company->users as $u)
                                    <tr>
                                        <td>{!! $n++ !!}</td>
                                        <td>{!! $u->id !!}</td>
                                        <td>{!! $u->name !!}</td>
                                        <td>{!! $u->employee_id !!}</td>
                                        <td>@if($u->status == 1) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
                                        <td>{!! isset($u->roles()->first()->display_name)?$u->roles()->first()->display_name:'' !!}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">Not Found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

