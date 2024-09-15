@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('control.panel')}}" class="text-capitalize text-chl">Control Panel</a>
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
                <div class="row">
                    <div class="col-sm-10 mb-1">
                        <label for="user">Select Users Name<span class="text-danger">*</span></label>
                        <select id="user" name="user" class="select-search cursor-pointer" multiple>
                            <option value="">Pick a state...</option>
                            @if(count($users))
                                @foreach($users as $user)
                                    <option value="{!! $user->id !!}">{!! $user->name !!} (ID: {!! $user->employee_id !!})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <button class="btn btn-chl-outline" type="button" id="ref-src-btn">
                            <i class="fa fa-user-plus"></i> Add To Company
                        </button>
                    </div>
                </div>
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
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Not Found</td>
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

