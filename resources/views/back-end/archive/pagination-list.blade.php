@extends('layouts.back-end.main')
@section('mainContent')
@if($voucherInfos)
    @php
        $d = @$voucherInfos->first()
    @endphp
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10 col-sm-9">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{!! $d->company->company_code !!}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{!! $d->VoucherType->voucher_type_title !!}</a>
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
                        <h4 class="text-capitalize d-inline-block"><i class="fa-solid fa-book"></i> Uploaded <b>{!! $d->VoucherType->voucher_type_title !!}</b> Document List (Company: {!! $d->company->company_code !!})</h4>
                        <a href="{!! route('uploaded.archive.list.quick') !!}" class="btn btn-sm btn-outline-primary float-end"><i class="fa-solid fa-bolt"></i> Quick List</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('back-end.archive._archive_pagination_list')
                    </div>
                    <div class="col-md-12 mt-3">
                        {{-- Items per page & total count --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @if ($voucherInfos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    Showing {{ $voucherInfos->firstItem() }} to {{ $voucherInfos->lastItem() }} of {{ $voucherInfos->total() }} results
                                @else
                                    Showing all {{ $voucherInfos->count() }} results
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
                        @if ($voucherInfos instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{-- Only show pagination links if it's a paginated result --}}
                            <div class="d-flex justify-content-center" id="pagination">
                                {{ $voucherInfos->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@stop
