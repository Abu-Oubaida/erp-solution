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
                        <div class="row md-1">
                            <h4 class="text-capitalize">Fixed Asset Opening</h4>
                            <hr>
                        </div>
                        <form>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label for="ref">Date<span class="text-danger">*</span></label>
                                        <input class="form-control" id="date" name="date" type="date" placeholder="date" value="" required/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label for="ref">Reference No<span class="text-danger">*</span></label>
                                        <input class="form-control" id="ref" name="ref" type="number" placeholder="Opening Reference No" value="{{old('ref')}}" required/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                                        <select placeholder="Pick a state..." id="project" name="project" class="cursor-pointer">
                                            <option value="">Pick a state...</option>
                                            @if(count($projects))
                                                @foreach($projects as $p)
                                                    <option value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label for="recourse_code">Materials<span class="text-danger">*</span></label>
                                        <select id="materials_id" name="materials_id" onchange="return Obj.getFixedAssetSpecification(this)">
                                            <option value="">Pick a state...</option>
                                            @if(count($fixed_assets))
                                                @foreach($fixed_assets as $fx)
                                                    <option value="{!! $fx->id !!}">{!! $fx->materials_name !!} ({{$fx->recourse_code}})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label for="specification">Specification<span class="text-danger">*</span></label>
                                        <select id="specification" name="specification">
                                            <option value="">Pick a state...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-1">
                                        <label for="ref">Unite<span class="text-danger">*</span></label>
                                        <input class="form-control" id="unite" name="unite" type="text" placeholder="unite" value="" required readonly/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-1">
                                        <label for="ref">Rate<span class="text-danger">*</span></label>
                                        <input class="form-control" id="rate" onfocusout="return Obj.priceTotal(this, 'qty','total')" type="text" placeholder="rate" value="" required/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-1">
                                        <label for="ref">Qty.<span class="text-danger">*</span></label>
                                        <input class="form-control" onfocusout="return Obj.priceTotal(this, 'rate','total')" id="qty" name="qty" type="text" placeholder="qty" value="" required/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-1">
                                        <label for="ref">Total<span class="text-danger">*</span></label>
                                        <input class="form-control" value="" id="total" type="text" placeholder="total" required readonly step="100"/>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-floating mb-3 mt-4 float-end">
                                        <button class="btn btn-chl-outline btn-sm" type="button" onclick="return Obj.fixedAssetOpeningAddList(this)"> <i class="fas fa-plus"></i> Add</button>
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

