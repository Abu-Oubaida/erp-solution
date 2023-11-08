@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <a class="btn btn-primary btn-sm float-end" href="{{route('branch.list')}}"><i class="fas fa-list-check"></i>  Branch List</a>
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
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                        </div>
                        <form method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="branch_name" name="branch_name" type="text" placeholder="Enter Branch Name" value="{{$branch->branch_name}}" required/>
                                        <label for="branch_name">Branch Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <div class="form-floating mb-4">
                                        <select class="form-control" id="branch_status" name="branch_status" required>
                                            <option value="1" @if($branch->status == 1) selected @endif>Active</option>
                                            <option value="0" @if($branch->status == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="branch_status">Branch Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="remarks" name="remarks" required>{!! $branch->remarks !!}
                                        </textarea>
                                        <label for="remarks">Remarks<span class="text-danger">*</span></label>
                                    </div>
                                </div>
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

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">List of Branch</h3>
                            <table class="table table-sm" id="datatablesSimple">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Branch Name</th>
                                    <th>Branch Type</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Remarks</th>
                                    <th><div class="text-center">Action</div></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($branches) && count($branches))
                                    @php
                                        $no= 1;
                                    @endphp
                                    @foreach($branches as $br)
                                        @if($br->id == $branch->id)
                                            <tr>
                                                <td><b>{!! $no++ !!}</b></td>
                                                <td><b>{!! $br->branch_name !!}</b></td>
                                                <td><b>{!! $br->branchType->title !!}</b></td>
                                                <td>@if($br->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                                                <td><b>{!! ($br->createdBy)?$br->createdBy->name:'' !!}</b></td>
                                                <td><b>{!! ($br->updatedBy)?$br->updatedBy->name:'' !!}</b></td>
                                                <td><b>{!! $br->remarks !!}</b></td>
                                                <td>
                                                    <div class="text-center">
                                                        <a href="{!! route('edit.branch',['branchID'=>\Illuminate\Support\Facades\Crypt::encryptString($br->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                                                        <form action="" class="display-inline" method="post">
                                                            @method('delete')
                                                            @csrf
                                                            <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($br->id) !!}">
                                                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>{!! $no++ !!}</td>
                                                <td>{!! $br->branch_name !!}</td>
                                                <td>{!! $br->branchType->title !!}</td>
                                                <td>@if($br->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                                                <td>{!! ($br->createdBy)?$br->createdBy->name:'' !!}</td>
                                                <td>{!! ($br->updatedBy)?$br->updatedBy->name:'' !!}</td>
                                                <td>{!! $br->remarks !!}</td>
                                                <td>
                                                    <div class="text-center">
                                                        <a href="{!! route('edit.branch',['branchID'=>\Illuminate\Support\Facades\Crypt::encryptString($br->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                                                        <form action="" class="display-inline" method="post">
                                                            @method('delete')
                                                            @csrf
                                                            <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($br->id) !!}">
                                                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-danger text-center">Not Found!</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@stop

