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
                <select placeholder="Pick a state..." id="project" name="project" class="select-search cursor-pointer">
                    <option value="">Pick a state...</option>
                    @if(count($projects))
                        @foreach($projects as $p)
                            <option value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2 mb-1">
                <label for="ref">Reference No<span class="text-danger">*</span></label>
                <input class="form-control" value="{!! Request::get('ref') !!}" type="text" placeholder="Reference number..." id="ref-src">
            </div>
            <div class="col-md-1 mt-4">
                <button class="btn btn-chl-outline" type="button" id="ref-src-btn" onclick="return Obj.fixedAssetOpeningSearch(this,'fixed-asset-body')">
                    <i class="fa fa-search"></i> search
                </button>
            </div>
            <div id="fixed-asset-body">
                @if(Request::get('ref'))
                    @include('back-end.asset._fixed_asset_opening_body')
                @endif
            </div>
        </div>

    </div>
@stop

