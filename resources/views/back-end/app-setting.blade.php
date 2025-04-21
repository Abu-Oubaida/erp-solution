@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        {{--    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
            </li>
            <li class="breadcrumb-item"><a
                    class="text-capitalize text-decoration-none">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
            </li>
        </ol>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="text-capitalize"><i
                                class="fas fa-cog"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize"><i class="fas fa-file-lines"></i> Data Archive Package List
                                </h3>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1" data-bs-toggle="modal"
                                        data-bs-target="#archive_package_modal"><i class="fas fa-plus"></i> Add Package
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="archive_package_list">
                            @include('back-end.archive-package._list')
                        </div>
                    </div>

                    <!--Add Archive Package Modal -->
                    <div class="modal fade" id="archive_package_modal" tabindex="-1"
                         aria-labelledby="archive_package_modallLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content" id="archive_package_modal_content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><i
                                            class="fas fa-file-circle-plus"></i> Add Data Archive Package</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label for="package_name">Package Name</label>
                                                <input type="text" class="form-control" id="package_name"
                                                       placeholder="Enter Package Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label for="package_size">Package Storage Size (GB)</label>
                                                <input type="text" class="form-control" id="package_size"
                                                       placeholder="Enter Package Storage Size">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-group">
                                                <label for="package_status">Status</label>
                                                <select class="form-select" id="package_status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-chl-outline float-end"
                                                    onclick="return AppSetting.addArchivePackage(this,'archive_package_list')">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Edit Archive Package Modal -->
                    <div class="modal fade" id="edit_archive_package_modal" tabindex="-1"
                         aria-labelledby="edit_archive_package_modallLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content" id="edit_archive_package_modal_content">

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

