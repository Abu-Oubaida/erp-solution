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
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">Fixed Asset</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>

        </div>
        <div class="row">
            <div class="col-md-2 mb-1">
                <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                <select id="project" name="project" class="select-search cursor-pointer">
                    <option value="">Pick a state...</option>
                    @if(count($projects))
                        @foreach($projects as $p)
                            <option @if(Request::get('project') !== null && Request::get('project') == $p->id)selected @endif value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-2 mb-1">
                <label for="ref">Reference No<span class="text-danger">*</span></label>
                <input class="form-control" value="{!! Request::get('ref') !!}" type="text" placeholder="Reference number..." id="ref-src">
            </div>

            <div class="col-md-2 mb-1">
                <label for="r_type">Reference Type<span class="text-danger">*</span></label>
                <select id="r_type" name="r_type" class="select-search cursor-pointer">
                    <option value="">Pick a state...</option>
                    @if(count($ref_types))
                        @foreach($ref_types as $rt)
                            <option @if(Request::get('rt') !== null && Request::get('rt') == $rt->id)selected @endif value="{!! $rt->id !!}">{!! $rt->name !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2 mt-4">
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.fixedAssetOpeningSearch(this,'fixed-asset-body')">
                    <i class="fa fa-search"></i> search
                </button>
            </div>
            <div id="fixed-asset-body">
                @if(isset($project_wise_ref))
                    @include('back-end.asset._fixed_asset_opening_project_wise_list')
                @endif
                @if((isset($reference) || isset($branchName)) && !(isset($project_wise_ref)))
                    @include('back-end.asset._fixed_asset_opening_body')
                @endif
            </div>
        </div>

    </div>
@stop

