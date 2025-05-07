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
                <div class="card-header mt-2">
                    <div class="row">
                        <h3 class="text-capitalize"><i class="fas fa-leaf"></i> Add Lead Information</h3>
                    </div>
                </div>
                <form id="leadForm">
                    <div class="card-body" id="commonSlot_for_multiple_step">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="company">Company Name<span class="text-danger">*</span></label>
                                    <select class="text-capitalize form-control company_dropdown" id="company_id" name="company">
                                        <option value="">Pick options...</option>
                                        @if (isset($companies) || count($companies) > 0)
                                            @foreach ($companies as $c)
                                                <option value="{{ $c->id }}">{{ $c->company_name }}
                                                    ({!! $c->company_code !!})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-md-4 mb-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="full_name" type="text" placeholder="Enter full name" />
                                            <label for="full_name">Full name <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="spouse" type="text"
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
                                            <input class="form-control" id="mobile_1" type="number" placeholder="Enter phone 1" />
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
                                            <input class="form-control" id="email_1" type="email"
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
                                        <button type="button" class="btn btn-chl-outline float-end mt-3" onclick="return Sales.addLeadStep1()"><i class="fa-solid fa-arrow-right"></i>
                                            Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
        </div>
    </div>
@stop
