@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
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
                            <h3 class="text-capitalize"><i class="fas fa-edit"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                    @if($type)
                            <form action="{!! route('op.reference.type.edit',['typeID'=>\Illuminate\Support\Facades\Crypt::encryptString($type->id)]) !!}" method="POST">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-sm-3 mb-1">
                                        <label for="company">Company<span class="text-danger">*</span></label>
                                        <select id="company" name="company" class="select-search cursor-pointer">
                                            <option value="{!! $type->company_id !!}">{!! @$type->company->company_name !!}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="op_ref_name" name="name" type="text" placeholder="Ope. Reference Type Name" value="{{$type->name}}" required/>
                                            <label for="op_ref_name">Ope. Reference Type Name<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="op_ref_code" name="code" type="text" placeholder="Ope. Reference Type Code" value="{{$type->code}}" required/>
                                            <label for="op_ref_code">Ope. Reference Type Code<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating mb-3">
                                            <select class="form-control" name="status" id="status">
                                                <option value="1" @if($type->status == 1) selected @endif>Active</option>
                                                <option value="0" @if($type->status == 0) selected @endif>Inactive</option>
                                            </select>
                                            <label for="status">Status<span class="text-danger">*</span></label>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" id="description" name="description"> {!! $type->description !!}</textarea>
                                            <label for="remarks">Description</label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-floating mt-3 float-end">
                                            <button type="submit" class="btn btn-chl-outline" name="submit" > <i class="fas fa-save"></i> Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    @endif
                    </div>
                </div>
            </div>
            <div class="col-md-13">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <h3 class="text-capitalize"><i class="fas fa-list"></i> Operation Reference Type List</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('back-end.programmer.__operation_ref_type_list')
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

