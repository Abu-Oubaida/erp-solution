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
                                <h3 class="text-capitalize"><i class="fas fa-plus"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-4">
                                <a class="btn btn-outline-primary btn-sm float-end mt-2" href="{!! route('designation.list') !!}"><i class="fas fa-list"></i> Show List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('edit.designation',['designationID'=>\Illuminate\Support\Facades\Crypt::encryptString($designation->id)]) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="title" name="title" type="text" placeholder="Enter title" value="{{$designation->title}}" required/>
                                        <label for="name">Designation title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="priority" name="priority" type="number" placeholder="Priority" value="{{$designation->priority}}" required/>
                                        <label for="priority">Priority<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if($designation->status == 1) selected @endif>Active</option>
                                            <option value="0" @if($designation->status == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class=" mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company">
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if($c->id == $designation->company_id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10">{{$designation->remarks}}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 float-end">
                                        <button type="submit" value="" class="btn btn-chl-outline mt-4" name="submit" > <i class="fas fa-save"></i> Update Designation</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                        @include('back-end.designation._list')
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

