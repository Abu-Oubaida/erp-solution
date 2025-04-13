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
        @if(auth()->user()->hasPermission('add_user_file_manager_permission'))
        <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            @if(!(@$user))
                                <div class="row">
                                    <div class="col-md-12 text-center text-danger">
                                        Not Found!
                                    </div>
                                </div>
                            @else
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
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6><b><i class="fas fa-folder"></i> File Manager Permission for </b>  User: {{$user->name}}</h6>
                                    </div>
                                    <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="company_id">For Company</label>
                                            <select class="form-control select-search" id="company_id" name="company_id" ref="{!! $user->id !!}" onchange="return Obj.companyDirectoryPermission(this)">
                                                <option value="">--Select Option--</option>
                                                @if(count($userCompanies))
                                                    @foreach($userCompanies as $uc)
                                                        <option value="{!! $uc->id !!}">{!! $uc->company_name !!}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dir">Directory</label>
                                            <select class="form-control select-search cursor-pointer" id="dir">
                                                <option value="">--Select folder--</option>
                                                @if(@$fileManagers && count($fileManagers))
                                                    @foreach($fileManagers as $file)
                                                        <option value="{!!$file!!}">{!! $file !!}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="childPermission">Child Permissions</label>
                                        
                                                <div class="d-flex gap-2"> 
                                                    <select class="form-control select-search cursor-pointer w-50" id="per" ref="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                        <option value="">--Permission--</option>
                                                        <option value="1">View Only</option>
                                                        <option value="2">Read/Write</option>
                                                        <option value="3">Read/Write/Delete</option>
                                                    </select>
                                        
                                                    <button class="btn btn-primary btn-chl w-20 h-50" id="perAdd" type="button"
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
                                </div>
                            </div>
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-10">
                                                <input type="checkbox" name="" id="select_all">
                                                <label for="select_all">Select All</label>
                                                <button class="btn btn-outline-danger btn-sm" name="submit_selected"
                                                    type="submit" value="delete"
                                                    onclick="return Obj.companyDirectoryPermissionMultipleDelete()"><i
                                                        class="fas fa-trash"></i>Delete Selected</button>

                                            </div>
                                            <table class="table table-sm" id="data-table">
                                                <thead>
                                                <tr>
                                                    <th rowspan="1" colspan="1">Select</th>
                                                    <th>No</th>
                                                    <th>Company</th>
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
                                </div>
                            </div>
                                @endif
                            @endif
                        </div>
                    </div>
                {{-- </div>
            </div> --}}
        </div>
    @endif
    </div>
@stop

