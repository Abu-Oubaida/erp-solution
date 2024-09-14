@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
{{--        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
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
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 class="text-capitalize"> <i class="fa-solid fa-list"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-4">
                                <a href="{!! route('add.role') !!}" class="btn btn-sm btn-outline-primary float-end"> <i class="fas fa-plus"></i> Add New Role</a>
                            </div>
{{--                            <div class="col">--}}
{{--                                <a class="btn btn-success btn-sm float-end mt-1 mb-1" href="{{route('user.list')}}"><i class="fas fa-list-check"></i>  User List</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.role._list')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

