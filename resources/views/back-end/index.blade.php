@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
{{--    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
{{--            <h1>Welcome to {{str_replace('-', ' ', config('app.name'))}} Smart Application</h1>--}}
            <h2><img src="{{url("image/logo/default/icon/360.png")}}" alt="{{str_replace('-', '-', config('app.name'))}}" class="img-fluid" width="7%"> Smart Solution. You are most welcome</h2>
        </div>
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3><i class="fas fa-user"></i> Login user information</h3>
                        </div>
                        <div class="card-body">
                            @if(!($user))
                                <div class="row">
                                    <div class="col-md-12 text-center text-danger">
                                        Not Found!
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-12">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <th>Name:</th>
                                                <td>{!! $user->name !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Access Role:</th>
                                                <td>{!! $user->roles->first()->display_name !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Employee ID:</th>
                                                <td>{!! $user->employee_id !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Designation:</th>
                                                <td>{!! isset($user->designation->title)?$user->designation->title:'' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Department:</th>
                                                <td>{!! isset($user->department->dept_name)?$user->department->dept_name:'' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{!! $user->email !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{!! $user->phone !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Account Status:</th>
                                                <td>@if($user->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer pb-1 pt-3">
                            <h3 class="text-center"><img src="{{url("image/logo/default/icon/360.png")}}" alt="{{str_replace('-', '-', config('app.name'))}}" class="img-fluid float-start" width="9%"> Thank you for using me.<img class="float-end" src="{{url("image/logo/default/icon/smile-icon.png")}}" alt=":)" width="11%"></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="col-md-12">
                                <h3 class=""><i class="fas fa-key"></i> Change password here</h3>
                            </div>
                        </div>
                        <form action="{!! route("change.password") !!}" method="post">
                        <div class="card-body">
                            @if(!($user))
                                <div class="row">
                                    <div class="col-md-12 text-center text-danger">
                                        Not Found!
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    @csrf
                                    <div class="col-md-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="old_password" name="old_password" type="password" placeholder="Old password here" />
                                            <label for="old_password">Old Password<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="new_password" name="new_password" type="password" placeholder="New password here" />
                                            <label for="new_password">New Password<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="form-floating">
                                            <input class="form-control" id="new_password_confirmation" name="new_password_confirmation" type="password" placeholder="New password confirmation here" />
                                            <label for="new_password_confirmation">New Password confirmation<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer pt-3 pb-2">
                            <img src="{{url("image/logo/default/icon/360.png")}}" alt="{{str_replace('-', '-', config('app.name'))}}" class="img-fluid mb-2" width="9.5%">
                            <div class="form-floating float-end">
                                <button type="submit" value="" onclick="return confirm('Are you sure to change the password?')" class="btn btn-chl-outline mt-1 mb-3" name="submit" ><i class="fas fa-save"></i> Update Password</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

