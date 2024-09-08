@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
{{--    <h1 class="mt- text-capitalize">{{str_replace('-', ' ', config('app.name'))}} | {{str_replace('.', ' ', \Route::currentRouteName())}} Page</h1>--}}
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
        </li>
    </ol>
    <div class="row">
        <div class="col-md-12" id="">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-8">
                            <h5><i class="fas fa-list"></i> Company Type list</h5>
                        </div>
                        <div class="col-md-4">
                            <a href="{!! route('add.company.type') !!}" class="btn btn-sm btn-outline-info float-end"><i class="fas fa-solid fa-plus"></i> Add New Company Type</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include("back-end.programmer._company-type-list")
                </div>
            </div>
        </div>
    </div>

</div>
@stop

