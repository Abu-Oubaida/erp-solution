@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">Fixed Assets</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="mb-0"><i class="fas fa-file"></i> Fixed Asset Report</h4>
                            </div>
                            <div class="col">
                                <button class="btn btn-sm btn-outline-primary float-end m-1 mb-0" type="button" id="ref-src-btn" onclick="return window.location.reload()">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                                <a href="{!! route('fixed.asset.stock.report') !!}" class="btn btn-sm btn-outline-success float-end m-1 mb-0" type="button" id="ref-src-btn">
                                    <i class="fa-solid fa-file-circle-plus"></i> New
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="company">Company<span class="text-danger">*</span></label>
                                <select id="company" name="company" class="select-search cursor-pointer" onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'projects')">
                                    <option value="">Pick option...</option>
                                    @if(count(@$companies))
                                        @foreach($companies as $c)
                                            <option @if(Request::get('c') !== null && Request::get('c') == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="projects">Projects Name<span class="text-danger">*</span></label>
                                <select id="projects" name="projects" class="select-search cursor-pointer" multiple onchange="Obj.projectWiseMaterials(this,'company','materials')">
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="materials">Materials<span class="text-danger">*</span></label>
                                <select id="materials" name="materials" class="select-search cursor-pointer" multiple>
                                    <option value="">Pick options...</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="from_date">From<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="from_date">
                            </div>
                            <div class="col">
                                <label for="to_date">To<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="to_date">
                            </div>

                            <div class="col mt-4">
                                <button class="btn btn-chl-outline float-end" type="button" id="ref-src-btn" onclick="return Obj.fixedAssetReportSearch(this,'fixed-asset-body')">
                                    <i class="fa fa-search"></i> search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-1">
{{--                    <div class="card-header">--}}
{{--                        <h4 class="mb-0"><i class="fas fa-file"></i> Report List</h4>--}}
{{--                    </div>--}}
                    <div class="card-body">
                        <div id="fixed-asset-body" class="mt-1">
                            <h5 class="text-center text-danger">Nothing to show</h5>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <script>
        @if(request()->get('from_c') || request()->get('to_c') || request()->get('to_p') || request()->get('from_p') || request()->get('ref'))
        (function ($) {
            $(document).ready(function () {
                Obj.gpEntrySearch(this,'fixed-asset-body')
            });
        }(jQuery))
        @endif
    </script>
@stop

