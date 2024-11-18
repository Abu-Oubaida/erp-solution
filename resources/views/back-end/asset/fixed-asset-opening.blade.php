@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">Fixed Asset</a>
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
                                <h4 class="mb-0"><i class="fas fa-edit"></i> With Reference Entry</h4>
                            </div>
                            <div class="col">
                                <button class="btn btn-outline-primary float-end btn-sm m-1 mb-0" type="button" id="ref-src-btn" onclick="return window.location.reload()">
                                    <i class="fa fa-refresh"></i> Refresh
                                </button>
                                <a href="{!! route('fixed.asset.distribution.opening.input') !!}" class="btn btn-outline-success float-end btn-sm m-1 mb-0" type="button" id="ref-src-btn">
                                    <i class="fa-solid fa-file-circle-plus"></i> New Entry
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label for="company">Company<span class="text-danger">*</span></label>
                                <select id="company" name="company" class="select-search cursor-pointer" onchange="return Obj.companyProjects(this,{!! Auth::user()->id !!},'project','r_type')">
                                    <option value="">Pick options...</option>
                                    @if(count($companies))
                                        @foreach($companies as $c)
                                            <option @if(Request::get('c') !== null && Request::get('c') == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col">
                                <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                                <select id="project" name="project" class="select-search cursor-pointer">
                                    <option value="">Pick options...</option>
                                    @if(@$projects !== null)
                                        @foreach($projects as $p)
                                            <option @if(Request::get('project') !== null && Request::get('project') == $p->id)selected @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col">
                                <label for="ref">Reference No<span class="text-danger">*</span></label>
                                <input class="form-control" value="{!! Request::get('ref') !!}" type="text" placeholder="Reference number..." id="ref-src">
                            </div>

                            <div class="col">
                                <label for="r_type">Reference Type<span class="text-danger">*</span></label>
                                <select id="r_type" name="r_type" class="select-search cursor-pointer">
                                    <option value="">Pick options...</option>
                                    @if(@$ref_types !== null)
                                        @foreach($ref_types as $rt)
                                            <option @if(Request::get('rt') !== null && Request::get('rt') == $rt->id)selected @endif value="{!! $rt->id !!}">{!! $rt->name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col mt-4">
                                <button class="btn btn-chl-outline float-end" type="button" id="ref-src-btn" onclick="return Obj.fixedAssetOpeningSearch(this,'fixed-asset-body')">
                                    <i class="fa fa-search"></i> search
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="fixed-asset-body" class="mt-1">
                @if(isset($fixed_asset_with_ref_report_list))
                    @include('back-end.asset._fixed_asset_opening_project_wise_list')
                @endif
                @if((isset($fixed_assets)) )
                    @include('back-end.asset._fixed_asset_opening_body')
                @endif
            </div>
        </div>

    </div>
@stop

