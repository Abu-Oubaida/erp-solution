@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
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
            <div class="col-md-2">
                <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize"> <i class="fa-solid fa-file-circle-plus"></i> {{str_replace('add','create',str_replace('.', ' ', \Route::currentRouteName()))}}</h3>
                            </div>
                            <div class="col">
                                <a class="btn btn-primary btn-sm float-end mt-1 mb-1" style="margin-left: 10px" href=""><i class="fa-solid fa-paper-plane"></i>  Sent List</a>
                                <a class="btn btn-success btn-sm float-end mt-1 mb-1 mr-1" href=""><i class="fa-solid fa-inbox"></i>  Received List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add.department') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.companyWiseUsersForReq(this,'user')">
                                            <option value="">--select a option--</option>
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-1">
                                    <label for="user">User Name<span class="text-danger">*</span></label>
                                    <select id="user" name="user" class="select-search cursor-pointer" onchange="" multiple>
                                        <option value="">Pick options...</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="deadline">Deadline<span class="text-danger">*</span></label>
                                        <input class="form-control" name="deadline" id="deadline" type="date" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label for="d_count">Number of Need Document</label>
                                        <input class="form-control" name="d_count" id="d_count" type="number" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-outline-primary float-end mt-4" onclick="Obj.document_field_operation(this)">Next <i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            </div>
                            <div class="row" id="document_field">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="subject">Subject<span class="text-danger">*</span></label>
                                        <input class="form-control" name="subject" id="subject" type="number" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10">{{old('remarks')}}</textarea>
                                        <label for="remarks">Details</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-chl float-end"><i class="fas fa-save"></i> Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
{{--                        @include('back-end.department._list')--}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

