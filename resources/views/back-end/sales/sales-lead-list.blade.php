@extends('layouts.back-end.main')
@section('mainContent')
<style>
    .dt-buttons{
        margin-left:0;
    }
    .dataTables_info{
        display: none;
    }
    .dataTables_paginate {
        display: none;
    }
</style>
@if($formattedLeads)
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10 col-sm-9">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2 col-sm-3 mb-1">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Lead Lists</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('back-end.sales.__sales-lead-lists-pagination')
                    </div>
                    <div class="col-md-12 mt-3">
                        {{-- Items per page & total count --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @if ($leadListDatum instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    Showing {{ $leadListDatum->firstItem() }} to {{ $leadListDatum->lastItem() }} of {{ $leadListDatum->total() }} results
                                @else
                                    Showing all {{ $leadListDatum->count() }} results
                                @endif
                            </div>
                            <form method="GET" class="d-flex align-items-center">
                                @foreach(request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label class="me-2 mb-0">Items per page:</label>
                                <select name="per_page" onchange="this.form.submit()" class="form-select form-select-sm w-auto">
                                    @foreach([10, 25, 50, 100, 300, 500, 1000] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                            {{ $size === 'all' ? 'All' : $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        {{-- Pagination --}}
                        @if ($leadListDatum instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{-- Only show pagination links if it's a paginated result --}}
                            <div class="d-flex justify-content-center" id="pagination">
                                {{ $leadListDatum->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@stop
