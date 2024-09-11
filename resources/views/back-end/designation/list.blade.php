@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
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

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 class="text-capitalize"><i class="fas fa-list"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-4">
                                <a href="{!! route('add.designation') !!}" class="btn btn-outline-primary btn-sm float-end mt-2"><i class="fas fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm" id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($designations) && count($designations))
                                @php
                                    $no= 1;
                                @endphp
                                @foreach($designations as $d)
                                    <tr>
                                        <td>{!! $no++ !!}</td>
                                        <td>{!! $d->title !!}</td>
                                        <td>{!! $d->priority !!}</td>
                                        <td>@if($d->status==1) Active @else Inactive @endif</td>
                                        <td>
                                            @if(auth()->user()->hasPermission('edit_designation'))
                                                <a href="{!! route('edit.designation',['designationID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                                            @endif
                                            @if(auth()->user()->hasPermission('delete_designation'))
                                                <form action="{{route('delete.designation')}}" class="display-inline" method="post">
                                                    @method('delete')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{!! $d->id !!}">
                                                    <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-danger text-center">Not Found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

