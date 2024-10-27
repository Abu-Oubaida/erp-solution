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
                            <h3 class="text-capitalize"><i class="fas fa-plus"></i> Operation Reference Type Add</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{!! route('op.reference.type') !!}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3 mb-1">
                                    <label for="company">Company<span class="text-danger">*</span></label>
                                    <select id="company" name="company" class="select-search cursor-pointer">
                                        <option value="">Pick options...</option>
                                        @if(count($companies))
                                            @foreach($companies as $c)
                                                <option @if(old('company') == $c->id)selected @endif value="{!! $c->id !!}">{!! $c->company_name !!} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="op_ref_name" name="name" type="text" placeholder="Ope. Reference Type Name" value="{{old('name')}}" required/>
                                        <label for="op_ref_name">Ope. Reference Type Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="op_ref_code" name="code" type="text" placeholder="Ope. Reference Type Code" value="{{old('code')}}" required/>
                                        <label for="op_ref_code">Ope. Reference Type Code<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status">
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                            <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="description" name="description"> {!! old('description') !!}</textarea>
                                        <label for="remarks">Description</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating mb-3 float-end mt-3">
                                        <button type="submit" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Add</button>
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

