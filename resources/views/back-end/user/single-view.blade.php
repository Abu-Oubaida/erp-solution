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
                                            <td>@foreach ($user->roles as $role)
                                                    {{ $roleNew = $role->display_name }}
                                                @endforeach</td>
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
                                <div class="col-md-8">
                                    <h4 class="text-center">Take Action Here</h4>
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
                                                                <option value="{{$r->id}}" @if($r->display_name == $roleNew) selected @endif>{!! $r->display_name !!}</option>
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
                                                                <option value="{{$dig->id}}" @if($dig->id == $user->designation_id) selected @endif>{!! $dig->title !!}</option>
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
                                                                <option value="{{$d->id}}" @if(isset($user->department->id) && $d->id == $user->department->id) selected @endif>{!! $d->dept_name !!}</option>
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
                                                                <option value="{{$branch->id}}" @if( isset($user->branch->id) && $branch->id == $user->branch->id) selected @endif>{!! $branch->branch_name !!}</option>
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
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h6><b><i class="fas fa-key"></i> All Module/Interface Permissions</b></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                    @if(strtolower($roleNew) == strtolower('superadmin'))
                        <div class="col-md-12">
                            <strong class="text-center text-info">Super Admin has all the permissions by default! </strong>
                        </div>
                    @else
                        <div class="col-md-12">
                            @if(!($user))
                                <div class="row">
                                    <div class="col-md-12 text-center text-danger">
                                        Not Found!
                                    </div>
                                </div>
                            @else

                                <div class="row">
                                    <form action="{!! route('add.user.permission') !!}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="user">For User</label>
                                                <select class="form-control select-search" id="user" name="user_id">
                                                    <option value="{!! $user->id !!}" selected>{!! $user->name !!}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="user">For Company</label>
                                                <select class="form-control select-search" id="user" name="user_id" onchange="Obj.companyChangeModulePermission(this)">
                                                    <option value="">--Select Option--</option>
                                        @if(count($userCompanies))
                                            @foreach($userCompanies as $uc)
                                                    <option value="{!! $uc->id !!}">{!! $uc->company_name !!}</option>
                                            @endforeach
                                        @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="parentPermission">Parent Permission</label>
                                                <select class="form-control select-search cursor-pointer" id="parentPermission" onchange="Obj.fiendPermissionChild(this,'childPermission')" name="parentPermission">
                                                    <option value="">--Select Option--</option>
{{--                                                    @if($permissionParents)--}}
{{--                                                        @foreach($permissionParents as $data)--}}
{{--                                                            <option value="{!! $data->id !!}">{!! $data->display_name !!}</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    @endif--}}
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="childPermission">Child Permissions</label>
                                                <select class="form-control select-search" id="childPermission" multiple="multiple" name="childPermission[]">
                                                    <option value="none">1. None</option>
                                                </select>
                                                <sub>Multiple Option choice-able  <span class="badge bg-secondary">Ctrl + </span> OR <span class="badge bg-primary">Shift + </span></sub>
                                            </div>
                                            <div class="col-md-12">
                                                <button class="btn btn-primary btn-chl float-end" id="perAdding" name="perAdding" type="submit" onclick="return confirm('Are you sure!')"><i class="fas fa-plus"></i> Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <br>
                                        <table id="datatablesSimple">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Parent Permission Name</th>
                                                <th>Child Permission Name</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Parent Permission Name</th>
                                                <th>Child Permission Name</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            @if(count($userPermissions))
                                                @php
                                                    $i=1;
                                                @endphp
                                                @foreach($userPermissions as $p)
                                                    <tr>
                                                        <td>{!! $i++ !!}</td>
                                                        <td>
                                                        <span class="text-capitalize">
                                                            {!! $p->permissionParent->display_name !!}
                                                        </span>
                                                        </td>
                                                        <td>
                                                        <span class="text-capitalize">
                                                            @if($p->is_parent == null) {!! /*str_replace('_',' ',$p->permission_name)*/ $p->permission_name!!}@endif
                                                        </span>
                                                        </td>

                                                        <td class="">
                                                            <form action="{!! route('remove.user.permission') !!}" class="display-inline" method="post">
                                                                @method('delete')
                                                                @csrf
                                                                <input type="hidden" name="user_id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($user->id) !!}">
                                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($p->id) !!}">
                                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this permission?')"><i class="fas fa-trash"></i></button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">Not found!</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h6><b><i class="fas fa-folder"></i> File Manager Permission</b></h6>
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
                            @if(strtolower($roleNew) == strtolower('superadmin'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong class="text-center text-info">Super Admin has all the permissions by default! </strong>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-12">
                                        <select class="form-control select-search cursor-pointer" id="dir">
                                            <option value="">--Select folder--</option>
                                            @if(@$fileManagers && count($fileManagers))
                                                @foreach($fileManagers as $file)
                                                    <option value="{!!$file!!}">{!! $file !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control select-search cursor-pointer" id="per" ref="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                            <option value="">--Permission--</option>
                                            <option value="1">View Only</option>
                                            <option value="2">Read/Write</option>
                                            <option value="3">Read/Write/Delete</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-sm btn-chl-outline float-end" id="perAdd" type="button" onclick="return confirm('Are you sure!')"><i class="fas fa-plus"></i> Add</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Directory</th>
                                                <th>Permission</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="f-p-list">
                                            @include("back-end.user._file-permission-list")
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
