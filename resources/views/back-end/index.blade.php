@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <h1>Welcome to {{str_replace('-', ' ', config('app.name'))}} Smart Application</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
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
                                    <thead>
                                    <tr>
                                        <th>User Information</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{!! $user->name !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Employee ID:</th>
                                        <td>{!! $user->employee_id !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Department:</th>
                                        <td>{!! $user->dept_name !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Access Role:</th>
                                        <td>{!! $user->display_name !!}</td>
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
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
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
                                <h4 class="text-center">Change Password Here</h4>
                            </div>
                            <form action="{!! route("change.password") !!}" method="post">
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
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="new_password_confirmation" name="new_password_confirmation" type="password" placeholder="New password confirmation here" />
                                        <label for="new_password_confirmation">New Password confirmation<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Update Password" onclick="return confirm('Are you sure to change the password?')" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </form>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

