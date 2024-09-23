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
            <div class="col-md-12" id="branch-add">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="text-capitalize"><i class="fas fa-plus"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-primary btn-sm float-end mt-1" href="#branch-list"><i class="fas fa-list-check"></i>  Branch List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add.branch') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <label for="company">Company Name <span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="company" name="company" onchange="return Obj.changeBranchCompany(this)">
                                        <option value="">Pick options...</option>
                                        @if(isset($companies) || (count($companies) > 0))
                                            @foreach($companies as $c)
                                                <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="branch_type">Branch Type<span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="branch_type" name="branch_type" required>
                                        <option value="">Pick options...</option>
{{--                                @if(count($branchTypeActive))--}}
{{--                                    @foreach($branchTypeActive as $type)--}}
{{--                                        <option value="{!! $type->id !!}">{!! $type->title !!}</option>--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="branch_name">Branch Name<span class="text-danger">*</span></label>
                                    <input class="form-control" id="branch_name" name="branch_name" type="text" placeholder="Enter Branch Name" value="{{old('branch_name')}}" required/>
                                </div>
                                <div class="col-md-3">
                                    <label for="branch_status">Branch Status<span class="text-danger">*</span></label>
                                    <select class="text-capitalize select-search" id="branch_status" name="branch_status" required>
                                        <option value="">Pick options...</option>
                                        <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                        <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating mb-2">
                                        <textarea class="form-control" id="address" name="address"> {{old('address')}}</textarea>
                                        <label for="address">Address</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating mb-2">
                                        <textarea class="form-control" id="remarks" name="remarks"> {{old('remarks')}}</textarea>
                                        <label for="remarks">Remarks</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mt-4 float-end">
                                        <button type="submit" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4" id="branch-list">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="text-capitalize"><i class="fas fa-list"></i> List of Branch</h3>
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-outline-info btn-sm float-end mt-1" href="#branch-add"><i class="fas fa-plus"></i>  Branch Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.branch._branch_list_data')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

