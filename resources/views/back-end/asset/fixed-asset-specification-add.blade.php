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
            <div class="col-md-5">
                <div class="card mb-2">
                    <div class="card-body">
                        <form action="{!! route('fixed.asset.specification') !!}" method="post">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-2">
                                        <input class="form-control form-control-sm" id="recourse_code" list="recourse_code_list" name="recourse_code" type="text" placeholder="Enter Recourse Code or Name" value="{{Request::get('code')}}" required/>
                                        <datalist id="recourse_code_list">
                                            @if(count($fixed_assets))
                                                @foreach($fixed_assets as $fx)
                                                    <option value="{!! $fx->recourse_code !!}">{!! $fx->materials_name !!}</option>
                                                @endforeach
                                            @endif
                                        </datalist>
                                        <label for="recourse_code">Materials Name<span class="text-danger">*</span></label>
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
            <div class="col-md-7">
                <div class="card mb-2">
                    <div class="card-body">
                        <form action="{!! route('fixed.asset.specification') !!}" method="post">
                            @csrf
                            @method('post')
                            <div class="row">
                                <input type="hidden" name="fixed_asset_id" value="{!! Request::get('fid') !!}">
                                <div class="col-md-8">
                                    <div class="form-floating mb-2">
                                        <input class="form-control form-control-sm" id="specification" name="specification" type="text" placeholder="Enter Specification" value="{{old('specification')}}" required/>
                                        <label for="specification">Specification<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <span><strong>Recourse Code:</strong> {!! Request::get('code') !!} ({!! Request::get('name') !!})</span>
                                    <div class="form-floating float-end">
                                        <button type="submit"  class="btn btn-chl-outline" value="addSpec" name="addSpec" ><i class="fas fa-save"></i> Add New</button>
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

