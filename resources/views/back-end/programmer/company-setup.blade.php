@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <h1 class="mt- text-capitalize">{{str_replace('-', ' ', config('app.name'))}} {{str_replace('.', ' ', \Route::currentRouteName())}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}" class="text-capitalize text-chl">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
        </li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <h1></h1>
        </div>
{{--        <div class="row">--}}
{{--            <div class="col-md-6">--}}
{{--                <div class="card mb-4">--}}
{{--                    <div class="card-body">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-12">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

</div>
@stop

