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
                        <form action="{{ route('add.designation') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input class="form-control" id="title" name="title" type="text" placeholder="Enter title" value="{{old('title')}}" required/>
                                        <label for="name">Designation title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input class="form-control" id="priority" name="priority" type="number" placeholder="Priority" value="{{old('priority')}}" required/>
                                        <label for="priority">Priority<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="status">Status<span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" name="status" id="status" required>
                                        <option value="">Pick options...</option>
                                        <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                        <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="company">Company Name <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company">
                                        <option value="">Pick options...</option>
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10">{{old('remarks')}}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mt-4 float-end">
                                        <button type="submit" value="" class="btn btn-chl-outline" name="submit" > <i class="fas fa-save"></i> Save Designation</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

