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
            <div class="col-md-3 mb-1">
                <label for="ref">Reference No<span class="text-danger">*</span></label>
                <div class="input-group">
                    <input class="form-control border-end-0 border" value="{!! Request::get('ref') !!}" type="text" placeholder="Reference number..." id="ref-src">
                    <span class="input-group-append">
                        <button class="btn btn-chl-outline bg-white border-start-0 border ms-n3" type="button" id="ref-src-btn" onclick="return Obj.fixedAssetOpeningSearch(this,'ref-src','fixed-asset-body')">
                            <i class="fa fa-search"></i> search
                        </button>
                    </span>
                </div>
            </div>
            <div id="fixed-asset-body">
                @if(Request::get('ref'))
                    @include('back-end.asset._fixed_asset_opening_body')
                @endif
            </div>
        </div>

    </div>
@stop

