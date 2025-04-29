@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">Data Archive Settings</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        @php
            use Illuminate\Support\Facades\Cache;
        @endphp
        @if(auth()->user()->isSystemSuperAdmin() || auth()->user()->companyWiseRoleName() == 'superadmin')
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                This page data is being fetched from cache. <a href="{!! route('clear.cache') !!}">Clear Cache</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(auth()->user()->hasPermission('add_archive_data_type_user_permission'))
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize"><i class="fas fa-key"></i> Data Type Permission</h3>
                            </div>
                            <div class="col">
                            @if(auth()->user()->hasPermission('archive_data_type_list'))
                                <a class="btn btn-primary btn-sm float-end mt-1" href="{!! route('archive.data.type.list') !!}"><i class="fa-solid fa-list"></i> Data Type List</a>
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="mb-1">
                                    <label for="company2">Company Name (Users) <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company2" name="company" onchange="return Obj.companyWiseDepartments(this,'company_wise_departments')">
                                        <option value="">--Please select a option--</option>
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5 mb-1">
                                <label for="department">Department Name (Users) <span class="text-danger">*</span></label>
                                <select id="company_wise_departments" multiple name="department" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)">
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex float-start align-items-center">
                                <a class="btn btn-sm btn-outline-secondary mt-2" id="search-icon" onclick="return Obj.searchCompanyDepartmentUsers('company2','company_wise_departments','permission_users')"><i class="fas fa-search"></i> Find Users</a>
                            </div>
                            <div class="col-md-4 mb-1">
                                <label for="department">User List <span class="text-danger">*</span></label>
                                <select id="permission_users" name="company_departments_users[]" class="select-search cursor-pointer" onchange="return Obj.selectAllOption(this)" multiple>
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="">
                                    <label for="company">Company Name (Type) <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjectsAndDataTypeArchive(this,null,'data_types',true)">
                                        <option value="">Pick options...</option>
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="">
                                    <label for="data_type">Type<span class="text-danger">*</span></label>
                                    <select class="form-control text-capitalize select-search" name="data_type" id="data_types" multiple onchange="Obj.selectAllOption(this)">
                                        <option value="">--Select a Type--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-chl-outline mt-4 float-end" onclick="return Archive.settingSetTypePermission(this,'company','data_types','permission_users')"><i class="fas fa-save"></i> Save Permission</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasPermission('archive_company_storage_management'))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h3><i class="fa-solid fa-chart-pie"></i> Company Wise Storage Information</h3></div>
                    <div class="card-body">
                        <table class="table table-hover" id="company_wise_storage_table">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company Name</th>
                                <th>Status</th>
                                <th>Total Storage</th>
                                <th>Used Storage</th>
                                <th>Free Storage</th>
                                @if(auth()->user()->isSystemSuperAdmin())
                                <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @if($company_wise_storage)
                                @php
                                $n = 1;
                                @endphp
                                @foreach($company_wise_storage as $c)
                                    <tr>
                                        <td>{{$n++}}</td>
                                        <td>{{$c['company_name']}}</td>
                                        <td>
                                            {!! $c['company_storage_package'] != null?$c['company_storage_package']['status']?"<span class='badge bg-success'>Active</span>":"<span class='badge bg-danger'>Inactive</span>":"<span class='badge bg-secondary'>Default</span>" !!}
                                        </td>
                                        <td>{!! $c['company_storage_package'] != null?($c['company_storage_package']['package_name'])?$c['company_storage_package']['package_name']. " ({$c['company_storage_package']['package_size']} GB)":"N/A": "N/A" !!}</td>
                                        <td>{!! $c['company_used_storage']. " GB" !!}</td>
                                        <td>{!! $c['company_storage_package'] != null ?($c['company_storage_package']['package_size'])?($c['company_storage_package']['package_size'] - $c['company_used_storage'])." GB": "N/A": "N/A" !!}</td>
                                        @if(auth()->user()->isSystemSuperAdmin())
                                        <td>
                                            <a href="#"
                                               class="btn btn-sm text-success"
                                               data-info='{"company":"{!! $c['company_name'] !!}","company_id":"{!! $c['company_id'] !!}","package_id":"{!! $c['company_storage_package']['package_id']??0 !!}","status":"{!!  $c['company_storage_package']?  $c['company_storage_package']['status']: null !!}"}'
                                               onclick="return Archive.companyPackageEdit(this)">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <script>
            (function ($) {
                $(document).ready(function () {
                    if (!$.fn.DataTable.isDataTable('#company_wise_storage_table')) {
                        $('#company_wise_storage_table').DataTable({
                            dom: 'lfrtip',
                            lengthMenu: [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, "ALL"]],
                            pageLength: 10,
                        })
                    }
                })
            }(jQuery))
        </script>
        @endif
    </div>
    @if(auth()->user()->isSystemSuperAdmin())
    <!-- Modal for details -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="remarksContent">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <select class="form-control" id="edit_company_name"></select>
                                <label for="edit_company_name">Edit Company Name</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <select class="form-control" id="edit_company_package">
                                @if(count($storage_packages))
                                    <option value="0">N/A</option>
                                @foreach($storage_packages as $sp)
                                    <option value="{!! $sp->id !!}">{!! $sp->package_name !!} ({!! $sp->package_size !!} GB)</option>
                                @endforeach
                                @endif
                                </select>
                                <label for="edit_company_name">Edit Company Package</label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-floating">
                                <select class="form-control" id="edit_company_status">
                                    <option value="-1">Default</option>
                                    <option value="0">Inactive</option>
                                    <option value="1">Active</option>
                                </select>
                                <label for="edit_company_status">Status</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-chl-outline float-end" onclick="return Archive.companyPackageUpdate()"><i class="fas fa-save"></i> Update</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop

