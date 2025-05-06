@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-end"><i
                class="fas fa-chevron-left"></i> Go Back</a>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{ route('control.panel') }}" class="text-capitalize text-chl">Control Panel</a>
            </li>
            <li class="breadcrumb-item"><a style="text-decoration: none;" href="#"
                    class="text-capitalize">{{ str_replace('.', ' ', \Route::currentRouteName()) }}</a></li>
        </ol>
        <div class="row">
            <div class="col-md-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Sales Employee Entry</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <label for="company">Company<span class="text-danger">*</span></label>
                                <select id="company" name="company" class="select-search"
                                    onchange="return Obj.companyWiseUsersForSalesEmployeeEntry(this,'user')">
                                    <option value="">Pick options...</option>
                                    @if (count($companies))
                                        @foreach ($companies as $c)
                                            <option @if (Request::get('c') !== null && Request::get('c') == $c->id) selected @endif
                                                value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="user">User Name<span class="text-danger">*</span></label>
                                <select class="text-capitalize select-search selectized" id="user" name="user[]"
                                    multiple="multiple" onchange="Obj.selectAllOption(this)">
                                    <option value="">Pick options...</option>
        
                                </select>
                            </div>
                            <div class="col-md-2 mb-1">
                                <label for="leader_id_of_specific_company">Leader<span class="text-danger">*</span></label>
                                <select id="leader_id_of_specific_company" name="leader_id_of_specific_company" class="form-control">
                                    <option value="">Select one...</option>
                                </select>
                            </div>
                            <div class="col-md-1 mb-1">
                                <button class="btn btn-primary btn-sm mt-4" type="button"
                                    onclick="return Sales.salesEmployeeEntry()">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Sales Employee List</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" id="partial_sell_employee_entry">
                                    @include('back-end.sales._sell-employee-entry')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Sales Leader Entry</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-1">
                                <label for="company">Company<span class="text-danger">*</span></label>
                                <select id="company_leader" name="company_leader" class="select-search"
                                    onchange="return Obj.companyWiseUsersForSalesEmployeeEntry(this,'leader')">
                                    <option value="">Pick options...</option>
                                    @if (count($companies))
                                        @foreach ($companies as $c)
                                            <option @if (Request::get('c') !== null && Request::get('c') == $c->id) selected @endif
                                                value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="leader">Leader Name<span class="text-danger">*</span></label>
                                <select class="text-capitalize select-search selectized" id="leader" name="leader[]"
                                    multiple="multiple" onchange="Obj.selectAllOption(this)">
                                    <option value="">Pick options...</option>
        
                                </select>
                            </div>
                            <div class="col-md-1 mb-1">
                                <button class="btn btn-primary btn-sm mt-4" type="button"
                                    onclick="return Sales.salesLeaderEntry()">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Leader List</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" id="partial_sell_leader_entry">
                                    @include('back-end.sales._sell-leader-entry')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="modal_to_update_leader_of_employee" tabindex="-1" aria-labelledby="archive_package_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generalModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="company">Leader Name<span class="text-danger">*</span></label>
                                <select class="text-capitalize form-control" id="update_leader" name="update_leader">
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col md-6 mt-4">
                            <button class="btn btn-primary" id="leader_update_btn">Update</button>
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
@stop
