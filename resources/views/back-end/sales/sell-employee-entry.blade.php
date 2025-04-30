@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        {{--    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1> --}}
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-capitalize text-chl">Dashboard</a>
            </li>
            <li class="breadcrumb-item"><a
                    class="text-capitalize text-decoration-none">{{ str_replace('.', ' ', \Route::currentRouteName()) }}</a>
            </li>
        </ol>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="text-capitalize"><i class="fas fa-cog" style="color:#4e73df"></i>
                            {{ str_replace('.', ' ', \Route::currentRouteName()) }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="general_modal" tabindex="-1" aria-labelledby="archive_package_modalLabel"
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
                                <label for="company">Company Name<span class="text-danger">*</span></label>
                                <select class="text-capitalize form-control company_dropdown" id="company" name="company" onclick="return SalesSetting.companyIdDropdownForEdit('sales_lead_location_info')">
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
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label for="company">Status</label>
                                <select class="text-capitalize form-control status_dropdown" id="status" name="status">
                                    <option value="">Pick options...</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" id="sales_sub_table_content">

                        </div>

                        <div class="col-md-12">
                            <button
                                class="btn btn-chl-outline float-end" id="perform_store" onclick="return SalesSetting.salesSubTableModal(this)">
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
    </div> --}}
@stop
