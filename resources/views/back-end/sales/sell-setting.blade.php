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
            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-building"
                                        style="color:#4e73df; font-size: 20px;"></i> Apartment Type List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_apartment_type')">
                                    <i class="fas fa-plus"></i> Add Apartment Type
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_apartment_type">
                            @include('back-end.sales.sales-lead-apartment-type-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-ruler-combined"
                                        style="color:#4e73df; font-size: 20px;"></i> Apartment Size List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_apartment_size')">
                                    <i class="fas fa-plus"></i> Add Apartment Size
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_apartment_size">
                            @include('back-end.sales.sales-lead-apartment-size-list')
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-list"
                                        style="color:#4e73df; font-size: 20px;"></i> View List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_view')">
                                    <i class="fas fa-plus"></i> Add View
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_view">
                            @include('back-end.sales.sales-lead-view-list')
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-money-bill-wave"
                                        style="color:#4e73df; font-size: 20px;"></i> Budget List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_budget')">
                                    <i class="fas fa-plus"></i> Add Budget
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_budget">
                            @include('back-end.sales.sales-lead-budget-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-share-nodes"
                                        style="color:#4e73df; font-size: 20px;"></i> Source List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_source_info')">
                                    <i class="fas fa-plus"></i> Add Source
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_source_info">
                            @include('back-end.sales.sales-lead-source-info-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-user-tie"
                                        style="color:#4e73df; font-size: 20px;"></i> Profession List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_profession')">
                                    <i class="fas fa-plus"></i> Add Profession
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_profession">
                            @include('back-end.sales.sales-lead-profession-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-map-marker-alt"
                                        style="color:#4e73df; font-size: 20px;"></i> Location Info List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_location_info')">
                                    <i class="fas fa-plus"></i> Add Location Info
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_location_info">
                            @include('back-end.sales.sales-lead-location-info-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-layer-group"
                                        style="color:#4e73df; font-size: 20px;"></i> Floor List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_floor')">
                                    <i class="fas fa-plus"></i> Add Floor
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_floor">
                            @include('back-end.sales.sales-lead-floor-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-check-circle"
                                        style="color:#4e73df; font-size: 20px;"></i> Status Info List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_status_info')">
                                    <i class="fas fa-plus"></i> Add Status Info
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_status_info">
                            @include('back-end.sales.sales-lead-status-info-list')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-2">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h6 style="font-size: 21px"><i class="fas fa-compass"
                                        style="color:#4e73df; font-size: 20px;"></i> Facing List</h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-success float-end mt-1 ms-2"
                                    onclick="return SalesSetting.salesSubTable('sales_lead_facing')">
                                    <i class="fas fa-plus"></i> Add Facing
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-3" id="output_sales_lead_facing">
                            @include('back-end.sales.sales-lead-facing-list')
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="general_modal" tabindex="-1" aria-labelledby="archive_package_modalLabel"
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
                                <select class="text-capitalize select-search company_dropdown" id="company" name="company"
                                    onchange="return SalesSetting.salesProfessionParentIdDropdown(this)">
                                    <option value="0">Pick options...</option>
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
                                <select class="text-capitalize select-search status_dropdown" id="status" name="status">
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
                                class="btn btn-chl-outline float-end"onclick="return SalesSetting.salesSubTableModal(this)">
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
@stop
