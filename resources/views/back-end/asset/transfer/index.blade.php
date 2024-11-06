@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">Fixed Assets</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}} via Gate Pass (GP)</a>
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
                                <h3><i class="fas fa-edit"></i> Gate Pass Entry</h3>
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-primary float-end m-1" type="button" id="ref-src-btn" onclick="return window.location.reload()">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                                <a href="{!! route('fixed.asset.transfer') !!}" class="btn btn-outline-info float-end m-1" type="button" id="ref-src-btn">
                                    <i class="fa-solid fa-file-circle-plus"></i> New Input
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col mb-1">
                                <label for="from_company">From Company<span class="text-danger">*</span></label>
                                <select id="from_company" name="from_company" class="select-search cursor-pointer" onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'from_project')">
                                    <option value="">Pick options...</option>
                                    @if(count(@$companies))
                                        @foreach($companies as $c)
                                            <option @if(Request::get('from_c') !== null && Request::get('from_c') == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col mb-1">
                                <label for="project">From Project Name<span class="text-danger">*</span></label>
                                <select id="from_project" name="from_project" class="select-search cursor-pointer">
                                    <option value="">Pick options...</option>
                            @if(@$from_projects !== null)
                                @foreach($from_projects as $p)
                                    <option @if(Request::get('from_p') !== null && Request::get('from_p') == $p->id)selected @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                @endforeach
                            @endif
                                </select>
                            </div>
                            <div class="col mb-1">
                                <label for="company">To Company<span class="text-danger">*</span></label>
                                <select id="to_company" name="to_company" class="select-search cursor-pointer" onchange="return Obj.userWiseCompanyProjectPermissions(this,{!! Auth::user()->id !!},'to_project')">
                                    <option value="">Pick options...</option>
                                    @if(count(@$companies))
                                        @foreach($companies as $c)
                                            <option @if(Request::get('to_c') !== null && Request::get('to_c') == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col mb-1">
                                <label for="project">To Project Name<span class="text-danger">*</span></label>
                                <select id="to_project" name="to_project" class="select-search cursor-pointer">
                                    <option value="">Pick options...</option>
                            @if(@$to_projects !== null)
                                @foreach($to_projects as $p)
                                    <option @if(Request::get('to_p') !== null && Request::get('to_p') == $p->id)selected @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                @endforeach
                            @endif
                                </select>
                            </div>
                            <div class="col mb-1">
                                <label for="ref">GP Reference No<span class="text-danger">*</span></label>
                                <input class="form-control" value="{!! Request::get('ref') !!}" type="text" placeholder="GP Reference number..." id="gp_ref">
                            </div>
                            <div class="col">
                                <label for="gp_date">GP Date<span class="text-danger">*</span></label>
                                <input class="form-control" value="{!! Request::get('d') !== null ?date('Y-m-d',strtotime(Request::get('d'))):'' !!}" name="gp_date" type="date"  id="gp_date">
                            </div>
                            <div class="col mt-4">
                                <button class="btn btn-chl-outline float-end" type="button" id="ref-src-btn" onclick="return Obj.gpEntrySearch(this,'fixed-asset-body')">
                                    <i class="fa fa-search"></i> search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="fixed-asset-body">
{{--                @if(isset($fixed_asset_with_ref_report_list))--}}
{{--                    @include('back-end.asset._fixed_asset_opening_project_wise_list')--}}
{{--                @endif--}}
{{--                @if((isset($fixed_assets)) )--}}
{{--                    @include('back-end.asset._fixed_asset_opening_body')--}}
{{--                @endif--}}
            </div>
        </div>

    </div>
    <script>
        @if(request()->get('from_c') && request()->get('to_c') && request()->get('to_p') && request()->get('from_p') && request()->get('ref') && request()->get('d'))
        (function ($) {
            $(document).ready(function () {
                Obj.gpEntrySearch(this,'fixed-asset-body')
            });
        }(jQuery))
        @endif
    </script>
@stop

