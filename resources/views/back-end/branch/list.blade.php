@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-12">
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
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3><i class="fas fa-list"></i> Branch List</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-info btn-sm float-end" href="{{route('add.branch')}}"><i class="fa-solid fa-plus"></i> Add Branch</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.branch._branch_list_data')
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3><i class="fas fa-list"></i> Branch Type List</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-info btn-sm float-end" href="{{route('add.branch.type')}}"><i class="fa-solid fa-plus"></i> Add Branch Type</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.branch._branch_type_list')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

