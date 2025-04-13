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
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-capitalize"><i class="fas fa-user-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-primary btn-sm float-end me-0" href="{{route('user.single.view',['userID'=>\Illuminate\Support\Facades\Crypt::encryptString($user->id)])}}"><i class="fas fa-eye"></i> Single View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.edit',["userID"=>\Illuminate\Support\Facades\Crypt::encryptString($user->id)]) }}" method="POST" enctype="multipart/form-data">
                                    @method('put')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="name" name="name" type="text" placeholder="Enter full name" value="@if(old('name')){!! old('name') !!}@else{{$user->name}}@endif"/>
                                                <label for="name">Full name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($user->id) !!}">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="phone" name="phone" type="text" placeholder="Enter phone number" value="@if(old('phone')){!! old('phone') !!}@else{!!  str_replace('','',($user->phone))!!}@endif"/>
                                                <label for="phone">Phone number <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter email address" value="@if(old('email')) {!! old('email') !!} @else {{$user->email}}  @endif"/>
                                                <label for="email">Email address <span class="text-danger">*</span></label>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-floating mb-3 float-end">
                                                <button type="submit" value="Update User" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Update User</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="row">
                                    <h6 class="text-capitalize"><i class="fas fa-edit"></i> Take Action Here</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{!! route("user.status.change") !!}" method="post">
                                            @csrf
                                            <div class="input-group float-end">
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <select class="form-control cursor-pointer" name="user_status" id="forwaed">
                                                    <option value="">--Select Option--</option>
                                                    <option value="1" @if($user->status) selected @endif>Active</option>
                                                    <option value="0" @if(!($user->status)) selected @endif>Inactive</option>
                                                </select>
                                                <button class="btn btn-primary btn-chl" onclick="return confirm('Are you sure change the user status?')" type="submit"> Change Status</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{!! route('user.role.change') !!}" method="post">
                                            @csrf
                                            <div class="input-group float-end">
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <select class="form-control cursor-pointer" name="user_role" id="forwaed">
                                                    <option value="">--Select Option--</option>
                                                    @if(isset($roles) && count($roles))
                                                        @foreach($roles as $r)
                                                            <option value="{{$r->id}}" @if(@$r->display_name == @$user->roles->first()->display_name) selected @endif>{!! $r->display_name !!} ({!! (isset($r->company->company_name)&&$r->company->company_name)? $r->company->company_name:'Default' !!})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <button class="btn btn-primary btn-chl" onclick="return confirm('Are you sure change the user role?')" type="submit"> Change Role</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{!! route('user.designation.change') !!}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group float-end">
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <select class="form-control cursor-pointer" name="designation_id" id="">
                                                    <option value="">--Select Option--</option>
                                                    @if(isset($designations) && count($designations))
                                                        @foreach($designations as $dig)
                                                            <option value="{{$dig->id}}" @if($dig->id == $user->designation_id) selected @endif>{!! $dig->title !!} ({!! $dig->company->company_name !!})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <button class="btn btn-primary btn-chl" onclick="return confirm('Are you sure change the user Designation?')" type="submit"> Change Designation</button>
                                            </div>
                                        </form>
                                        <br>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{!! route('user.dept.change') !!}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group float-end">
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <select class="form-control cursor-pointer" name="dept_id" id="">
                                                    <option value="">--Select Option--</option>
                                                    @if(isset($deptLists) && count($deptLists))
                                                        @foreach($deptLists as $d)
                                                            <option value="{{$d->id}}" @if(isset($user->department->id) && $d->id == $user->department->id) selected @endif>{!! $d->dept_name !!} ({!! $d->company->company_name !!})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <button class="btn btn-primary btn-chl" onclick="return confirm('Are you sure change the user Department?')" type="submit"> Change Department</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <form action="{!! route('user.branch.change') !!}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group float-end">
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <select class="form-control cursor-pointer" name="branch_id" id="">
                                                    <option value="">--Select Option--</option>
                                                    @if(isset($branches) && count($branches))
                                                        @foreach($branches as $branch)
                                                            <option value="{{$branch->id}}" @if( isset($user->branch->id) && $branch->id == $user->branch->id) selected @endif>{!! $branch->branch_name !!} ({!! $branch->company->company_name !!})</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <button class="btn btn-primary btn-chl" onclick="return confirm('Are you sure change the user Breach?')" type="submit"> Change Branch</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{!! route('user.password.change') !!}" method="post">
                                            @csrf
                                            <div class="input-group float-end">
                                                <input type="hidden" name="id" value="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <input class="form-control" type="password" name="password" id="">
                                                <button class="btn btn-primary btn-chl" onclick="return confirm('Are you sure change the user password?')" type="submit"> Change Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

