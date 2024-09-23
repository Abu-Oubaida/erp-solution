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
                        <a href="{{route('add.branch')}}" class="text-capitalize text-chl">Add Branch</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('branch.list')}}" class="text-capitalize text-chl">Branch List</a>
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
                                <h3 class="text-capitalize"><i class="fas fa-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-primary btn-sm float-end" href="{{route('branch.list')}}"><i class="fas fa-list-check"></i>  Branch List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="company">Company Name <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company">
                                        <option value="{{$branch->company->id}}">{{$branch->company->company_name}} ({!! $branch->company->company_code !!})</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="branch_name" name="branch_name" type="text" placeholder="Enter Branch Name" value="{{$branch->branch_name}}" required/>
                                        <label for="branch_name">Branch Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-4">
                                        <select class="form-control" id="branch_type" name="branch_type" required>
                                    @if(count($branchTypeActive))
                                        @foreach($branchTypeActive as $type)
                                            <option value="{!! $type->id !!}" @if($branch->branch_type == $type->id) selected @endif>{!! $type->title !!}</option>
                                        @endforeach
                                    @endif
                                        </select>
                                        <label for="branch_type">Branch Type<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-4">
                                        <select class="form-control" id="branch_status" name="branch_status" required>
                                            <option value="1" @if($branch->status == 1) selected @endif>Active</option>
                                            <option value="0" @if($branch->status == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="branch_status">Branch Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="address" name="address" >{!!$branch->address!!}
                                        </textarea>
                                        <label for="address">Address</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="remarks" name="remarks" >{!!$branch->remarks!!}
                                        </textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mt-3 float-end">
                                        <button type="submit" class="btn btn-chl-outline" name="submit" > <i class="fas fa-save"></i> Update</button>
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
                                <h3 class="text-capitalize"><i class="fas fa-list"></i> Branch List</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-info btn-sm float-end" href="{{route('add.branch')}}"><i class="fas fa-plus"></i>  Branch Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include("back-end.branch._branch_list_data")
                    </div>
                </div>
            </div>

        </div>

    </div>
@stop

