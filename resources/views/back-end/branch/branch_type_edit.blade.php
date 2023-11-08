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
                        <form action="" method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="branch_type_title" name="branch_type_title" type="text" placeholder="Enter Branch Type Title" value="{{$branchType->title}}" required/>
                                        <label for="branch_type_title">Branch Type Title<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="branch_type_code" name="branch_type_code" type="text" placeholder="Enter Branch Type Code" value="{{$branchType->code}}"/>
                                        <label for="branch_type_code">Branch Type Code</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-4">
                                        <select class="form-control" id="branch_type_status" name="branch_type_status" required>
                                            <option value="1" @if($branchType->status == 1) selected @endif>Active</option>
                                            <option value="0" @if($branchType->status == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="branch_type_status">Branch Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="remarks" name="remarks">{!! $branchType->remarks !!}</textarea>
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
                            <h3 class="text-capitalize">List of Branch Type</h3>
                            <table class="table table-sm" id="datatablesSimple2">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Code</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($branchTypeAll) && count($branchTypeAll))
                                    @php
                                        $no= 1;
                                    @endphp
                                    @foreach($branchTypeAll as $b)
                                        @if($branchType->id == $b->id)
                                            <tr>
                                                <td><b>{!! $no++ !!}</b></td>
                                                <td><b>{!! $b->title !!}</b></td>
                                                <td>@if($b->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                                                <td><b>{!! $b->code !!}</b></td>
                                                <td><b>{!! ($b->createdBy)?$b->createdBy->name:'' !!}</b></td>
                                                <td><b>{!! ($b->updatedBy)?$b->updatedBy->name:'' !!}</b></td>
                                                <td><b>{!! $b->remarks !!}</b></td>
                                                <td>
                                                    @if(auth()->user()->hasPermission('edit_branch_type'))
                                                        <a href="{!! route('edit.branch.type',['branchTypeID'=>\Illuminate\Support\Facades\Crypt::encryptString($b->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                                                    @endif
                                                    <form action="{{route('delete.branch.type')}}" class="display-inline" method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($b->id) !!}">
                                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>{!! $no++ !!}</td>
                                                <td>{!! $b->title !!}</td>
                                                <td>@if($b->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                                                <td>{!! $b->code !!}</td>
                                                <td>{!! ($b->createdBy)?$b->createdBy->name:'' !!}</td>
                                                <td>{!! ($b->updatedBy)?$b->updatedBy->name:'' !!}</td>
                                                <td>{!! $b->remarks !!}</td>
                                                <td>
                                                    @if(auth()->user()->hasPermission('edit_branch_type'))
                                                        <a href="{!! route('edit.branch.type',['branchTypeID'=>\Illuminate\Support\Facades\Crypt::encryptString($b->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                                                    @endif
                                                    <form action="{{route('delete.branch.type')}}" class="display-inline" method="post">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($b->id) !!}">
                                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-danger text-center">Not Found!</td>
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

