@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-12">
                <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
                <div class="row">
                    <div class="col-md-10">
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">
                                <a href="{{\Illuminate\Support\Facades\URL::current()}}" class="text-capitalize text-chl">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-2">
                        <div class="float-end">
                            {!! ($complain->user_id == \Illuminate\Support\Facades\Auth::user()->id)?"<a href='".route('single.view.complain',['complainID'=>\Illuminate\Support\Facades\Crypt::encryptString($complain->id)])."' class='btn btn-success btn-sm w-auto' role='button'><i class='fas fa-eye'></i> View</a>":''!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                </div>
                <form action="{{ route('edit.me.complain',["complainID"=>\Illuminate\Support\Facades\Crypt::encryptString($complain->id)]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="complain_title" name="complain_title" type="text" placeholder="Enter complain title" value="{{$complain->title}}"/>
                                <label for="complain_title">Complain Title <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-control text-capitalize" id="priority" name="priority">
                            @if(count($priorities))
                                @foreach($priorities as $priority)
                                    <option class="text-capitalize" value="{{$priority->priority_number}}" @if($priority->id == $complain->priority) selected @endif>{{$priority->title}}</option>
                                @endforeach

                            @endif
                                </select>
                                <label for="priority">Complain Priority</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="to" name="to">
                                    <option value=""></option>
                            @if(count($depts))
                                @foreach($depts as $d)
                                    <option class="text-capitalize" value="{{$d->id}}" @if($d->id == $complain->to_dept) selected @endif>{{$d->dept_name}}</option>
                                @endforeach
                            @endif
                                </select>
                                <label for="priority">To Department <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="status" name="status">
                                    <option value=""></option>
                                    <option value="1" @if($complain->status == 1) selected @endif>Active</option>
                                    <option value="7" @if($complain->status == 7) selected @endif>Delete</option>
                                </select>
                                <label for="status">Status <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea name="content" id="editor" cols="50">{!! $complain->details !!}</textarea>
                            </div>
                        </div>
                        <input type="hidden" name="ref" value="{!! $complain->id !!}">
                        <div class="col-md-12">
                            <div class="form-floating mb-3 float-end">
                                <input type="submit" value="Update" class="btn btn-chl-outline" name="submit" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

