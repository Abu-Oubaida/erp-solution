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
                            <div class="col-sm-8">
                                <h3 class="text-capitalize"> <i class="fa-solid fa-plus"></i> {{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                            </div>
                            <div class="col-sm-4">
                                <a href="{!! route('role.list') !!}" class="btn btn-sm btn-outline-info float-end"> <i class="fas fa-list"></i> Role List</a>
                            </div>
{{--                            <div class="col">--}}
{{--                                <a class="btn btn-success btn-sm float-end mt-1 mb-1" href="{{route('user.list')}}"><i class="fas fa-list-check"></i>  User List</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add.role') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="name" name="name" type="text" placeholder="Enter role name" value="{{old('name')}}" required/>
                                        <label for="name">Role Name<span class="text-danger">*</span> <small>[ Only acceptable character are " a-z and 0-9_ " ]</small></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="display_name" name="display_name" type="text" placeholder="Enter role display name" value="{{old('display_name')}}" required/>
                                        <label for="name">Role Display Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class=" mb-3">
                                        <label for="company">Company Name <span class="text-danger">*</span></label>
                                        <select class="text-capitalize select-search" id="company" name="company">
                                            @if(isset($companies) || (count($companies) > 0))
                                                @foreach($companies as $c)
                                                    <option value="{{$c->id}}" @if(old('company') == $c->id) selected @endif>{{$c->company_name}} ({!! $c->company_code !!})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{old('description')}}</textarea>
                                        <label for="remarks">Description</label>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-floating mt-3 float-end">
                                        <button  type="submit" value="" class="btn btn-chl-outline" name="submit" ><i class="fas fa-save"></i> Save Role</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

