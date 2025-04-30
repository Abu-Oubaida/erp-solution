@extends('layouts.back-end.main')
@section('mainContent')
    {{-- <script>
        $(function {
            $(document).ready(function() {
                
            })
        })(jQuery)
    </script> --}}
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#"
                            class="text-capitalize">{{ str_replace('.', ' ', \Route::currentRouteName()) }}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <a href="{{ \Illuminate\Support\Facades\URL::previous() }}" class="btn btn-danger btn-sm float-end"><i
                        class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 card">
                {{-- @include('back-end.sales._add-lead-stage-1') --}}
                <div class="card-header mt-2">
                    <div class="row">
                        <h3 class="text-capitalize"><i class="fas fa-leaf"></i> Add Lead Information</h3>
                    </div>
                </div>
                <form id="leadForm">
                    <div class="card-body" id="commonSlot_for_multiple_step">
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="name" type="text" placeholder="Enter full name" />
                                            <label for="name">Full name <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="relation" type="text"
                                                placeholder="Enter husband/wife name" />
                                            <label for="relation">Husband/Wife </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="primary_mobile" type="number" placeholder="Primary Mobile"
                                                value="" />
                                            <label for="primary_mobile">Primary Mobile <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input class="form-control" id="phone2" type="number" placeholder="Enter phone 1" />
                                            <label for="phone2">Alternative Mobile 1</label>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <a href="#" class="float-end text-decoration-none"
                                            onclick="Sales.addEmailPhoneForLead(this,'mobile','add_extra_info')"><i
                                                class="fas fa-plus small"></i> Add
                                            Another Mobile</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="primary_email" type="email" placeholder="Primary Email"
                                                value="" />
                                            <label for="primary_email">Primary Email <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input class="form-control" id="alternative_email_1" type="email"
                                                placeholder="Enter Email 2" />
                                            <label for="alternative_email_1">Alternative Email 1</label>
                                        </div>
                                    </div>
                
                                    <div class="col-12 mt-1">
                                        <a href="#" class="float-end text-decoration-none"
                                            onclick="Sales.addEmailPhoneForLead(this,'email','add_extra_info')"><i
                                                class="fas fa-plus small"></i> Add
                                            Another
                                            Email</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mb-3">
                                    <div class="form-floating mb-3">
                                        <textarea type="text" class="form-control" id="notes" name="notes" placeholder="Notes"></textarea>
                                        <label for="Designation">Notes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row" id="add_extra_info">
                
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-chl-outline float-end mt-3"><i class="fa-solid fa-arrow-right"></i>
                                            Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
        </div>




        <div class="row">
            <div class="col-md-6">
                <h6># Source Information</h6>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="source" list="source-list" name="source"
                                placeholder="Source">
                            <datalist id="source-list">
                                <option value="None">None</option>
                                <option value="Newspaper">Newspaper</option>
                                <option value="Hotline">Hotline</option>
                                <option value="Social">Social</option>
                                <option value="Reference">Reference</option>
                            </datalist>
                            <label for="branch">Main Source <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="source" list="source-list" name="source"
                                placeholder="Source">
                            <datalist id="source-list">
                                <option value="None">None</option>
                                <option value="Newspaper">Newspaper</option>
                                <option value="Hotline">Hotline</option>
                                <option value="Social">Social</option>
                                <option value="Reference">Reference</option>
                            </datalist>
                            <label for="branch">Source <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="team-leader" class="form-control" name="team_leader">
                                <option value="">--Select Option--</option>
                                <option value="db_leader_id">Leader 1</option>
                                <option value="db_leader_id">Leader 2</option>
                            </select>
                            <label for="team-leader">Team Leader <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="associate" class="form-control" name="associate">
                                <option value="">--Select Option--</option>
                                <option value="db_associate_id">Associate 1</option>
                                <option value="db_associate_id">Associate 2</option>
                            </select>
                            <label for="associate">Associate <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="recorded_by" id="recorded_by" class="form-control">
                            <label for="recorded_by">Recorded By</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="rating" class="form-control" name="rating">
                                <option value="">--Select Option--</option>
                                <option value="db_rating_id">Rating 1</option>
                                <option value="db_rating_id">Rating 2</option>
                            </select>
                            <label for="rating">Rating</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="industry" class="form-control" name="industry">
                                <option value="">--Select Option--</option>
                                <option value="db_industry_id">Industry 1</option>
                                <option value="db_industry_id">Industry 2</option>
                            </select>
                            <label for="industry">Industry</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="branch" class="form-control" name="branch">
                                <option value="">--Select Option--</option>
                                @if (count($branches))
                                    @foreach ($branches as $branch)
                                        <option value="{!! $branch->id !!}">{!! $branch->branch_name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="branch">Branch Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="lead-creat-dept" class="form-control" name="lead_create_dept">
                                <option value="">--Select Option--</option>
                                @if (count($depts))
                                    @foreach ($depts as $dept)
                                        <option value="{!! $dept->id !!}">{!! $dept->dept_name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="lead-creat-dept">Lead Create Department</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h6># Prospect Preference</h6>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <div class="form-floating mb-3">
                                <textarea type="text" class="form-control" id="preference-notes" name="p_notes" placeholder="Preference Notes"></textarea>
                                <label for="preference-notes">Preference Notes</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select id="apartment-type" class="form-control" name="apartment_type">
                                <option value="">--Select Option--</option>
                                <option value="db_apartment_type_id">apartment type 1</option>
                                <option value="db_apartment_type_id">apartment type 1</option>
                            </select>
                            <label for="associate">Apartment Type </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <span for="preferred-location">Preferred Location </span>
                        <div class="form-floating mb-3">
                            <select class="text-capitalize select-search" id="preferred_location"
                                name="preferred_location[]" multiple>
                                {{-- @if (old('company') == $c->id) selected @endif --}}
                                <option value="">--select option--</option>
                                @if (isset($leadWiseLocations) || count($leadWiseLocations) > 0)
                                    @foreach ($leadWiseLocations as $c)
                                        <option value="{{ $c['id'] }}"> {!! $c['dept_name'] !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-control" id="apartment-size" name="apartment_size">
                                <option value="">--Select--</option>
                                <option value="db_size_id">1200 sft</option>
                                <option value="db_size_id">1300 sft</option>
                                <option value="db_size_id">1600 sft</option>
                            </select>
                            <label for="apartment-size">Apartment Size </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select class="form-control" id="apartment-floor" name="apartment_floor">
                                <option value="">--Select--</option>
                                <option value="1">1st Floor</option>
                                <option value="2">2nd Floor</option>
                                <option value="3">3rd Floor</option>
                                <option value="4">4th Floor</option>
                            </select>
                            <label for="apartment-floor">Apartment Floor </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-control" id="apartment-facing" name="apartment_facing">
                                <option value="">--Select--</option>
                                <option value="south">South</option>
                                <option value="north">North</option>
                                <option value="east">East</option>
                                <option value="west">West</option>
                            </select>
                            <label for="apartment-facing">Facing </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-control" id="apartment-view" name="apartment_view">
                                <option value="">--Select--</option>
                                <option value="south">South</option>
                                <option value="north">North</option>
                                <option value="east">East</option>
                                <option value="west">West</option>
                            </select>
                            <label for="apartment-view">View </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="budget" id="budget">
                            <label for="budget">Budget </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3 float-end">
                    <input type="submit" value="Insert User" class="btn btn-chl-outline" name="submit">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal modal-xl fade" id="myModal" tabindex="-1" aria-labelledby="userDataModelLabel"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-fullscreen-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userDataModelLabel">Title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="window.location.reload()"></button>
                </div>
                <div class="modal-body">
                    <table id="data-table" class="table"></table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="window.location.reload()">Close</button>
                    <button type="button" class="btn btn-primary"
                        onclick="return confirm('Are you sure?'), Obj.userExcelFileSubmit(this)">Save
                        changes</button>
                </div>
            </div>
        </div>
        <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
            <img width="50%" src="{{ url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif') }}" />
        </div>
    </div>

@stop
