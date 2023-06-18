@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <h1>Welcome to {{str_replace('-', ' ', config('app.name'))}} Smart Application</h1>
        </div>
    </div>
</div>
@stop

