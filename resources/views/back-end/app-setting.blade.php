@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
{{--    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>--}}
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a></li>
        <li class="breadcrumb-item"><a class="text-capitalize text-decoration-none">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="text-capitalize"><i class="fas fa-cog"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
        </div>
        <div class="card-body">
            <div class="row mt-3">

            </div>
        </div>
    </div>

</div>
@stop

