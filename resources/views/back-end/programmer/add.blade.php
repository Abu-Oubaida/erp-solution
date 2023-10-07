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
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="{!! route('permission.input') !!}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating mb-">
                                        <select class="form-control" name="permission_parent" id="permission_parent" required>
                                            <option value="null">Null</option>
                                            @foreach($permissions as $p)
                                                <option value="{!! $p->id !!}">{!! $p->display_name !!}</option>
                                            @endforeach
                                        </select>
                                        <label for="permission_parent">Permission Parent<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="permission_name" name="permission_name" type="text" placeholder="Enter Permission Name" value="{{old('permission_name')}}" required/>
                                        <label for="permission_name">Permission_Name</label>
                                        <sub>Permission name must be seperated by under_score ( a_b )</sub>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="permission_display_name" name="permission_display_name" type="number" placeholder="Enter Permission Display Name" value="{{old('permission_display_name')}}" required/>
                                        <label for="permission_display_name">Display Name</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="description" name="description"> {!! old('description') !!}</textarea>
                                        <label for="remarks">Description</label>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Insert" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <table id="datatablesSimple">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Parent</th>
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Details</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Parent</th>
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Details</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @if(isset($permissions) && count($permissions))
                        @php
                            $no= 1;
                        @endphp
                        @foreach($permissions as $data)
                            <tr>
                                <td>{!! $no !!}</td>
                                <td>{!! $data->display_name   !!}</td>
                                <td>{!! $data->name   !!}</td>
                                <td>{!! $data->display_name   !!}</td>
                                <td>{!! $data->description   !!}</td>
                                <td>{!! date('d-M-y',strtotime($data->created_at))   !!}</td>
                                <td>{!! date('d-M-y',strtotime($data->updated_at))   !!}</td>
                                <td>
                                    <form action="{{route('permission.input.delete')}}" class="display-inline" method="post">
                                        @method('delete')
                                        @csrf
                                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}">
                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the parent permission?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @php($a=1)
                            @foreach($data->childPermission as $child)
                                <tr>
                                    <td><div class="text-end">{!! $no !!}.{!! $a++ !!}</div></td>
                                    <td>{!! $data->display_name   !!}</td>
                                    <td>{!! $child->name   !!}</td>
                                    <td>{!! $child->display_name   !!}</td>
                                    <td>{!! $child->description   !!}</td>
                                    <td>{!! date('d-M-y',strtotime($child->created_at))   !!}</td>
                                    <td>{!! date('d-M-y',strtotime($child->updated_at))   !!}</td>
                                    <td>
                                        <form action="{{route('permission.input.delete')}}" class="display-inline" method="post">
                                            @method('delete')
                                            @csrf
                                            <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($child->id) !!}">
                                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the child permission?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @php($no++)
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
@stop

