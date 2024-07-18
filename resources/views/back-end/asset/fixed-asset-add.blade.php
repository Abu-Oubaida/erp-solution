@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <div class="row">
            <div class="col-md-10">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="{{ route('fixed.asset.add') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="recourse_code" name="recourse_code" type="text" placeholder="Enter Recourse Code" value="{{old('recourse_code')}}" required/>
                                        <label for="recourse_code">Recourse Code<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="materials" name="materials" type="text" placeholder="Materials Name" value="{{old('materials')}}" required/>
                                        <label for="materials">Materials Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="rate" name="rate" type="number" placeholder="Materials Rate" value="{{old('rate')}}" required/>
                                        <label for="rate">Materials Rate<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="unit" name="unit" type="text" placeholder="Materials Unit" value="{{old('unit')}}" required/>
                                        <label for="unit">Materials Unit<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="depreciation" name="depreciation" type="text" placeholder="Depreciation Rate" value="{{old('depreciation')}}" />
                                        <label for="depreciation">Depreciation Rate (%)</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10">{{old('remarks')}}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Submit" class="btn btn-chl-outline" name="submit" >
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
                        @include('back-end.asset._fixed-asset-list')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

