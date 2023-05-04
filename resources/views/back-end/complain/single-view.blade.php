@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{\Request::path()}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
            </li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if(!($com))
                            <div class="row">
                                <div class="col-md-12 text-center text-danger">
                                    Not Found!
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <span>Problem Title:</span>
                                        <h3>
                                            {!! $com->title !!}
                                        </h3>
                                        <div>
                                            <span>Priority: @if($com->priority == 1) {!! '<span class="badge bg-primary">Normal</span>' !!} @elseif($com->priority == 2) {!! '<strong class="badge bg-warning">Urgent</strong>' !!} @elseif($com->priority == 3) {!! '<strong class="badge bg-danger">Very Urgent</strong>' !!} @else {!! '<span class="badge bg-success">Lazy</span>' !!} @endif</span>
                                            <span>Status: @if($com->status == 1) {!! '<span class="badge bg-primary">Active</span>' !!} @elseif($com->status == 2) {!! '<strong class="badge bg-warning">Processing</strong>' !!} @elseif($com->status == 3) {!! '<strong class="badge bg-danger">Solved</strong>' !!}@elseif($com->status == 4) {!! '<strong class="badge bg-info">Pending</strong>' !!} @elseif($com->status == 5) {!! '<strong class="badge bg-danger">Reject</strong>' !!} @else {!! '<span class="badge bg-success">Unknown</span>' !!} @endif</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row"></div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12 problem-details">
                                    <span>Problem Details:</span>
                                    {!! $com->details !!}
                                </div>


                            </div>
                            <div class="row">

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
