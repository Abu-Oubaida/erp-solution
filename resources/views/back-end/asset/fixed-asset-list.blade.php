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
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 class="text-capitalize"><i class="fas fa-list"></i> Fixed asset list</h3>
                            </div>
                            <div class="col-sm-4">
                                <a href="{{route('fixed.asset.add')}}" class="btn btn-outline-primary btn-sm float-end mt-2"><i class="fa fa-plus"></i> Add Fixed Asset</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.asset._fixed-asset-list')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

