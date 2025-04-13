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
        @if (auth()->user()->hasPermission('add_user_screen_permission'))
            <div class="col-md-12">
                <div class="row">
                    @if ($roleNew === 'systemsuperadmin')
                    <div class="col-md-10 mx-auto">
                    <div class="card">
                        <div class="card-body text-center">
                                <strong class="text-info">System/Super Admin has all the permissions by default!
                                </strong>
                        </div>
                    </div>
                    </div>
                    @else
                        <div class="col-md-12">
                            @if (!@$user)
                                <div class="row">
                                    <div class="col-md-12 text-center text-danger">
                                        Not Found!
                                    </div>
                                </div>
                            @else
                                <div class="card">
                                    <div class="card-header">
                                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6><b><i class="fas fa-key"></i> User screen permission (Module/Interface)</b></h6>
                                            </div>
                                            @if (auth()->user()->hasPermission('user_screen_permission'))
                                            <div class="d-flex gap-2">
                                                <div>
                                                    <a class="btn btn-success btn-sm" href="{{route('add.user')}}"><i class="fa-solid fa-circle-plus"></i>Add User</a>
                                                </div>
                                                <div>
                                                    <a href="{{route('user.single.view',Crypt::encryptString($userID))}}" class="btn btn-primary btn-sm" title="View"><i class="fas fa-eye"></i>View User</a>
                                                </div>
                                                <div>
                                                    <a class="btn btn-secondary btn-sm" type="button" href="{{route('user.edit',Crypt::encryptString($userID))}}" title="Edit"><i class="fas fa-edit"></i>Edit User</a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <form action="{!! route('add.user.permission') !!}" method="post">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="user">For User</label>
                                                        <select class="form-control select-search" id="user_id"
                                                            name="user_id">
                                                            <option value="{!! $user->id !!}" selected>
                                                                {!! $user->name !!}</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="company">For Company</label>
                                                        <select class="form-control select-search" id="company"
                                                            name="company_id"
                                                            onchange="Obj.companyChangeModulePermission(this)">
                                                            <option value="">--Select Option--</option>
                                                            @if (count($userCompanies))
                                                                @foreach ($userCompanies as $uc)
                                                                    <option value="{!! $uc->id !!}">
                                                                        {!! $uc->company_name !!}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="parentPermission">Parent Permission</label>
                                                        <select class="form-control select-search cursor-pointer"
                                                            id="parentPermission"
                                                            onchange="Obj.fiendPermissionChild(this,'childPermission')"
                                                            name="parentPermission" multiple>
                                                            <option value="">--Select Option--</option>
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="childPermission">Child Permissions</label>
                                                    
                                                            <div class="d-flex gap-2"> 
                                                                <select class="form-control select-search w-50" id="childPermission"
                                                                    multiple name="childPermission[]"
                                                                    onchange="return Obj.selectAllOption(this)">
                                                                    <option value="none">1. None</option>
                                                                </select>
                                                    
                                                                <button class="btn btn-primary btn-chl w-20 h-50" id="perAdding" name="perAdding" type="submit"
                                                                    onclick="return confirm('Are you sure!')">
                                                                    <i class="fas fa-plus"></i> Add
                                                                </button>
                                                            </div>
                                                    
                                                            <sub>
                                                                Multiple Option choice-able
                                                                <span class="badge bg-secondary">Ctrl + </span> OR
                                                                <span class="badge bg-primary">Shift + </span>
                                                            </sub>
                                                        </div>
                                                    </div>



                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-10">
                                                    <input type="checkbox" name="" id="select_all">
                                                    <label for="select_all">Select All</label>
                                                    <button class="btn btn-outline-danger btn-sm" name="submit_selected"
                                                        type="submit" value="delete"
                                                        onclick="return Obj.deleteUserPermissionMultiple({{ $userID }})"><i
                                                            class="fas fa-trash"></i>Delete Selected</button>

                                                </div>
                                                <br>
                                                <div id="user-permissions-container">
                                                    @include('back-end.user.user_screen_permission_table')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@stop
