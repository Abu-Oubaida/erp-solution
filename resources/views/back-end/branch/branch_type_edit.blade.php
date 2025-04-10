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
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="text-capitalize"> <i class="fas fa-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-primary btn-sm float-end" href="{{route('branch.type.list')}}"><i class="fas fa-list-check"></i>  Branch Type List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="company">Company Name <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company">
                                        <option value="{{$branchType->company->id}}">{{$branchType->company->company_name}} ({!! $branchType->company->company_code !!})</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="branch_type_title" name="branch_type_title" type="text" placeholder="Enter Branch Type Title" value="{{$branchType->title}}" required/>
                                        <label for="branch_type_title">Branch Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="branch_type_code" name="branch_type_code" type="text" placeholder="Enter Branch Type Code" value="{{$branchType->code}}"/>
                                        <label for="branch_type_code">Branch Type Code</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-4">
                                        <select class="form-control" id="branch_type_status" name="branch_type_status" required>
                                            <option value="1" @if($branchType->status == 1) selected @endif>Active</option>
                                            <option value="0" @if($branchType->status == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="branch_type_status">Branch Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="remarks" name="remarks">{!! $branchType->remarks !!}</textarea>
                                        <label for="remarks">Remarks<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mt-4 float-end">
                                        <button type="submit" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Update</button>
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
                            <div class="col-sm-10">
                                <h3> <i class="fas fa-list"></i> Branch Type List</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-info btn-sm float-end" href="{{route('add.branch.type')}}"><i class="fas fa-plus"></i>  Add Branch Type</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.branch._branch_type_list')
                    </div>
                </div>
            </div>

        </div>

    </div>
@stop

