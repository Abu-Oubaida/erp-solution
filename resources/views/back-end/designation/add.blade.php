@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
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
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="{{ route('add.designation') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="title" name="title" type="text" placeholder="Enter title" value="{{old('title')}}" required/>
                                        <label for="name">Designation title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="priority" name="priority" type="number" placeholder="Priority" value="{{old('priority')}}" required/>
                                        <label for="priority">Priority<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                            <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class=" mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company">
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}">{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10">{{old('remarks')}}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <button type="submit" value="" class="btn btn-chl-outline" name="submit" > <i class="fas fa-save"></i> Save Designation</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
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

