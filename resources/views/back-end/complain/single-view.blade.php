@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <div class="row">
            <div class="col-md-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="text-capitalize text-chl">Previous</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <label for="forwaed">Forward to: &nbsp;</label>
                            <select class="form-control" name="" id="forwaed">
                                <option value="">--Select Option--</option>
                                <option value="0">None</option>
                            </select>
                            <button class="btn btn-primary btn-chl" id="btnNavbarSearch" type="button"> <i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                            <span>Status: @if($com->status == 1) {!! '<span class="badge bg-primary">Active</span>' !!} @elseif($com->status == 2) {!! '<strong class="badge bg-warning">Processing</strong>' !!} @elseif($com->status == 3) {!! '<strong class="badge bg-danger">Solved</strong>' !!}@elseif($com->status == 4) {!! '<strong class="badge bg-info">Pending</strong>' !!} @elseif($com->status == 5) {!! '<strong class="badge bg-danger">Reject</strong>' !!} @elseif($com->status >= 6) {!! '<strong class="badge bg-danger">Deleted</strong>' !!} @else {!! '<span class="badge bg-success">Unknown</span>' !!} @endif</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row float-end">
                                        @if($com->status == 1)
                                            {!! ($com->user_id == \Illuminate\Support\Facades\Auth::user()->id)?"<a href='".route('edit.my.complain',['complainID'=>\Illuminate\Support\Facades\Crypt::encryptString($com->id)])."' class='btn btn-primary btn-sm w-auto' role='button'><i class='fas fa-edit'></i> Edit</a>":''!!}
                                        @endif
                                    </div>
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
