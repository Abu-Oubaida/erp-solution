@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-10 col-sm-9">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('data.archive.dashboard.interface')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2 col-sm-3 mb-1">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="company">Company Name <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseProjectsAndDataTypeArchive(this,'projects','data_types',true)">
                                        <option value="">--select option--</option>
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}" @if($c->id == request()->get('c')) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="">
                                    <label for="projects">Projects</label>
                                    <select class="form-control text-capitalize select-search" name="projects" id="projects" multiple>
                                        <option value="">--Select options--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-1">
                                <div class="">
                                    <label for="data_types">Data Types</label>
                                    <select class="form-control text-capitalize select-search" name="data_type" id="data_types" multiple>
                                        @if(request()->get('t') && isset($dataType))
                                            <option value="{!! $dataType->id !!}" selected>{!! $dataType->voucher_type_title !!}</option>
                                        @endif
                                        <option value="">--Select options--</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 mb-1">
                        <div class="">
                            <label for="from_date">From Date</label>
                            <input class="form-control" type="date" name="from_date" id="from_date">
                        </div>
                    </div>
                    <div class="col-md-2 mb-1">
                        <div class="">
                            <label for="to_date">To Date</label>
                            <input class="form-control" type="date" name="to_date" id="to_date">
                        </div>
                    </div>
                    <div class="col-md-2 mb-1">
                        <div class="">
                            <label for="reference">Reference</label>
                            <input class="form-control" type="text" name="reference" id="reference">
                        </div>
                    </div>
                    @if(!(request()->get('t') && isset($dataType) && request()->get('c')))
                    <div class="col-md-1">
                        <button class="float-end btn btn-chl-outline mt-4" onclick="return Obj.archiveDataQuickSearch(this)"><i class="fas fa-search"></i> Search</button>
                    </div>
                    @else
                        <div class="col-md-1">
                            <button
                                class="float-end btn btn-outline-danger mt-4"
                                onclick="if(confirm('Are you sure?')) { window.location.href='{{ route('uploaded.archive.list.quick') }}'; } return false;"
                            >
                                <i class="fas fa-power-off"></i> Reset
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="d-inline-block mb-0"><i class="fa-solid fa-bolt"></i> Search Result</h3>
                        @if(auth()->user()->hasPermission('archive_document_upload'))
                            <a class="btn btn-outline-success btn-sm mt-1 float-end" href="{{route("add.archive.info")}}"><i class="fas fa-upload"></i> Uploaded New</a>
                        @endif
                    </div>
                </div>
            </div>
            @php
            $request = request()
            @endphp
            @if ($request->get('c') && $request->get('t'))
                <script>
                    (function ($) {
                        $(document).ready(function () {
                            Archive.typeWiseDataView(this,{!! $request->get('c') !!},{!! $request->get('t') !!},'quick-list')
                        });
                    }(jQuery))
                </script>
            @endif
            <div class="card-body" id="quick-list">
                <h5 class="text-center text-danger">No result are found!</h5>
{{--                @if(isset($voucherInfos))--}}
{{--                    @include('back-end.archive._archive_quick_list')--}}
{{--                @endif--}}
            </div>
        </div>
    </div>
@stop
