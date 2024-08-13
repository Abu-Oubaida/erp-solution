@extends('layouts.back-end.main')
@section('mainContent')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-capitalize" >Welcome to {{str_replace('-', ' ', config('app.name'))}}˚ {{str_replace('.', ' ', \Route::currentRouteName())}}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a></li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if(auth()->user()->hasPermission('control_panel'))
                    <h5># Control Panel</h5>
                    <ol>
                        @if(auth()->user()->hasPermission('add_user_project_permission')) @endif
                        <li><a class="text-decoration-none text-black" href="{!! route('add.user.project.permission') !!}">User Project Permission</a></li>
                    </ol>
                    @endif
                </div>
                <div class="col-md-4">
                    <h5># Sales Interface</h5>
                    <ol >
                    @if(auth()->user()->hasPermission('create_sales_team'))
                        <li><a class="text-decoration-none text-black" href="">Create Team</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('assign_sales_marketing_users'))
                        <li><a href="" class="text-decoration-none text-black">Assign Sales Marketing Users</a></li>
                    @endif
                    </ol>
                </div>
                <div class="col-md-4">
                    <h5># Accounts Interface</h5>
                </div>
            </div>
        </div>
    </div>

</div>
@stop

