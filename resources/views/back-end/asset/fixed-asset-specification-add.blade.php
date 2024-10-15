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
                        <a href="{{route('fixed.asset.show')}}" class="text-capitalize text-chl">{!! str_replace('-',' ',Request::segment(1)) !!}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-2">
                    <div class="card-header">
                        <h3><i class="fa-solid fa-toolbox"></i> Select Material Here</h3>
                    </div>
                    <div class="card-body">
                        <form action="{!! route('fixed.asset.specification') !!}" method="post">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseFixedAssets(this,'recourse_code')">
                                            <option value="">Pick options...</option>
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if( Request::get('c') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="recourse_code">Materials Name<span class="text-danger">*</span></label>
                                        <select class="select-search" name="recourse_code" id="recourse_code" required>
                                            @if(isset($fixed_assets) && count($fixed_assets))
                                                @foreach($fixed_assets as $fx)
                                                    <option value="{!! $fx->recourse_code !!}" @if(Request::get('code')  == $fx->recourse_code) selected @endif>{!! $fx->materials_name !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <a class="float-end" href="{!! route('fixed.asset.add') !!}"><i class="fas fa-plus"></i> Add Fixed Asset</a>
                                    <a class="float-start btn btn-sm btn-outline-danger" href="{!! route('fixed.asset.specification') !!}"><i class="fas fa-refresh"></i> Reset</a>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating float-end ml-2">
                                        <button type="submit" class="btn btn-sm btn-chl-outline" value="search" name="search" ><i class="fas fa-search"></i> Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3><i class="fa-solid fa-hammer"></i> Add Materials Specification</h3>
                            </div>
                            <div class="col-sm-4">
                                <a href="{!! route('fixed.asset.add') !!}" class="btn btn-outline-primary btn-sm mt-2 float-end"><i class="fas fa-plus"></i> Add Materials</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <input type="hidden" id="fixed_asset_id" value="{!! Request::get('fid') !!}">
                                <input type="hidden" id="company_id" value="{!! Request::get('c') !!}">
                                <div class="col-md-8">
                                    <div class="form-floating mb-2">
                                        <input class="form-control form-control-sm" id="specification" type="text" placeholder="Enter Specification" value="{{old('specification')}}" required/>
                                        <label for="specification">Specification<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" id="status" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span><strong>Recourse Code:</strong> {!! Request::get('code') !!} ({!! Request::get('name') !!})</span>
                                    <div class="form-floating float-end">
                                        <button type="button"  class="btn btn-chl-outline" value="addSpec" name="addSpec" onclick="return Obj.fixedAssetSpecificationStore(this)"><i class="fas fa-save"></i> Add New</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="text-capitalize"><i class="fas fa-list"></i> Fixed Asset Specification List</h3>
                        </div>
                    </div>
                    <div class="card-body" id="specification_data">
                        @include('back-end.asset._fixed-asset-specification-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

