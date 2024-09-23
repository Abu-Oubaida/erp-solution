@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
        <div class="row">
            <div class="col-md-12">
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
            <div class="col-md-12" id="branch-type-add">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="text-capitalize"> <i class="fa-solid fa-plus"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-primary btn-sm float-end mt-1" href="#branch-type-list"><i class="fas fa-list-check"></i>  Branch List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add.branch.type') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="company">Company Name <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company">
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-2">
                                        <input class="form-control" id="branch_type_title" name="branch_type_title" type="text" placeholder="Enter Branch Type Title" value="{{old('branch_type_title')}}" required/>
                                        <label for="branch_type_title">Branch Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-2">
                                        <input class="form-control" id="branch_type_code" name="branch_type_code" type="text" placeholder="Enter Branch Type Code" value="{{old('branch_type_code')}}"/>
                                        <label for="branch_type_code">Branch Type Code</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-2">
                                        <select class="form-control" id="branch_type_status" name="branch_type_status" required>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <label for="branch_type_status">Branch Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-2">
                                        <textarea class="form-control" id="remarks" name="remarks">{!! old('remarks') !!}</textarea>
                                        <label for="remarks">Remarks<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-2 float-end">
                                        <input type="submit" value="Add" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="text-capitalize"><i class="fas fa-list"></i> List of Branch Type</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-info btn-sm float-end mt-1" href="#branch-type-add"><i class="fas fa-plus"></i>  Branch Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="branch-type-list">
                        @include('back-end.branch._branch_type_list')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

