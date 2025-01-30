@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{!! route('user.list') !!}" class="text-capitalize text-chl">User List</a>
                </li>
                <li class="breadcrumb-item">
                    <a style="text-decoration: none;" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3><i class="fas fa-user"></i> User Information</h3>
                        </div>
                        <div class="col-sm-6">
                            <a class="btn btn-outline-success btn-sm float-end ms-1 mt-1" href="{{route('add.user')}}"><i class="fa-solid fa-circle-plus"></i> Add User</a>
                            <a class="btn btn-outline-info btn-sm float-end m-1" href="{{route('user.list')}}"><i class="fas fa-list-check"></i>  User List</a>
                            <a class="btn btn-outline-primary btn-sm float-end m-1" href="{{route('user.edit',['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><i class="fas fa-edit"></i>  User Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        @if(!($user))
                            <div class="row">
                                <div class="col-md-12 text-center text-danger">
                                    Not Found!
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-4">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <th>Name:</th>
                                            <td>{!! $user->name !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Joining Date:</th>
                                            <td>{!! date('d-M-Y',strtotime($user->joining_date)) !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Employee ID:</th>
                                            <td>{!! $user->employee_id !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Department:</th>
                                            <td>{!! isset($user->department->dept_name)?$user->department->dept_name:'' !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Access Role:</th>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    {{ $roleNew = @$role->display_name }}
                                                @endforeach
                                                <b>({!! $user->getCompany->company_name !!})</b>
                                            </td>
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
            </div>
        </div>
    </div>

</div>
@stop
