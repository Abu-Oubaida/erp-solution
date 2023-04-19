@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            This page is an example of using the light side navigation option. By appending the
            <code>.sb-sidenav-light</code>
            class to the
            <code>.sb-sidenav</code>
            class, the side navigation will take on a light color scheme. The
            <code>.sb-sidenav-dark</code>
            is also available for a darker option.
        </div>
    </div>
</div>
@stop

