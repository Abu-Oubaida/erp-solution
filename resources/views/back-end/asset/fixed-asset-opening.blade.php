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
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row md-2">
                            <h4 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h4>
                            <hr>
                        </div>
                        <form action="{{ route('fixed.asset.add') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="ref">Opening Reference No<span class="text-danger">*</span></label>
                                        <input class="form-control" id="ref" name="ref" type="number" placeholder="Opening Reference No" value="{{old('ref')}}" required/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                                        <select placeholder="Pick a state..." id="project" name="project" class="cursor-pointer">
                                            <option value="">Pick a state...</option>
                                            @if(count($projects))
                                                @foreach($projects as $p)
                                                    <option value="{!! $p->branch_name !!}">{!! $p->branch_name !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="recourse_code">Materials<span class="text-danger">*</span></label>
                                        <select id="recourse_code" name="recourse_code">
                                            <option value="">Pick a state...</option>
                                            @if(count($fixed_assets))
                                                @foreach($fixed_assets as $fx)
                                                    <option value="{!! $fx->recourse_code !!}">{!! $fx->materials_name !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="specification">Specification<span class="text-danger">*</span></label>
                                        <select id="specification" name="specification">
                                            <option value="">Pick a state...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-floating mb-3 mt-4 float-end">
                                        <button class="btn btn-chl-outline btn-sm" > <i class="fas fa-plus"></i> Add</button>
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
{{--                        @include('back-end.asset._fixed-asset-list')--}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

