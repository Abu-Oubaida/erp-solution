@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
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
        <div class="card mb-4">
            <div class="card-header text-capitalize">
                <div class="row">
                    <div class="col">
                        <h3><i class="fas fa-list"></i> Uploaded data list</h3>
                    </div>
                    <div class="col">
                        <div class="float-end mt-1">
                            @if(auth()->user()->hasPermission('add_archive_document'))
                                <a class="btn btn-success btn-sm" href="{{route('add.archive.info')}}"><i class="fas fa-upload" aria-hidden="true"></i> New Upload</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @include('back-end.account-voucher._voucher_list')
            </div>
        </div>
    </div>
@stop
