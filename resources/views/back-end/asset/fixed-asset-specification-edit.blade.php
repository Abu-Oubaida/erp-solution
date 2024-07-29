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
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <form action="{!! route('edit.fixed.asset.specification',['fasid'=>\Illuminate\Support\Facades\Crypt::encryptString($fas->id)]) !!}" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <input type="hidden" name="fixed_asset_id" value="{!! (isset($fas->fixed_asset_id))?$fas->fixed_asset_id:'' !!}">
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <select class="form-control form-control-sm" id="fixed_asset_id" required disabled>
                                            <option value="{!! (isset($fas->fixed_asset_id))?$fas->fixed_asset_id:'' !!}" selected>{!! (isset($fas->fixed_asset->materials_name))?$fas->fixed_asset->materials_name:'' !!} ({!! (isset($fas->fixed_asset->recourse_code))?$fas->fixed_asset->recourse_code:'' !!})</option>
                                        </select>
                                        <label for="fixed_asset_id">Materials<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-2">
                                        <input class="form-control form-control-sm" id="specification" name="specification" type="text" placeholder="Enter Specification" value="{{(isset($fas->specification))?$fas->specification:''}}" required/>
                                        <label for="specification">Specification<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="0" @if(isset($fas->status) && $fas->status == 0) selected @endif>Inactive</option>
                                            <option value="1" @if(isset($fas->status) && $fas->status == 1) selected @endif>Active</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span><strong>Recourse Code:</strong> {!! (isset($fas->fixed_asset->recourse_code))?$fas->fixed_asset->recourse_code:'' !!}</span>
                                    <div class="form-floating float-end">
                                        <button type="submit"  class="btn btn-chl-outline" value="updateSpec" name="updateSpec" ><i class="fas fa-save"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        @include('back-end.asset._fixed-asset-specification-list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

