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
            <div class="col-md-2">
                <div class="float-end">
                    <a class="btn btn-success btn-sm" href="{{route('user.list')}}"><i class="fas fa-list-check"></i>  User List</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-floating mb-">
                                        <input class="form-control" id="simOperator" name="simOperator" type="text" placeholder="Enter sim operator" value="{{old('simOperator')}}" required/>
                                        <label for="simOperator">Sim Operator<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="number" name="number" type="number" placeholder="Enter number" value="{{old('number')}}" required/>
                                        <label for="number">Number<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="type" id="type">
                                            <option value="0">--Select SIM Type--</option>
                                            <option value="1">Postpaid</option>
                                            <option value="2">Prepaid</option>
                                        </select>
                                        <label for="type">Number<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-floating mb-3">
                                        <select class="form-control" name="status" id="status" required>
                                            <option value=""></option>
                                            <option value="1" @if(old('status') == 1) selected @endif>Active</option>
                                            <option value="0" @if(old('status') == 0) selected @endif>Inactive</option>
                                        </select>
                                        <label for="status">Status<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="ceilingOP" name="ceilingOP" type="text" placeholder="Ceiling set by Operator" value="{{old('ceilingOP')}}"/>
                                        <label for="ceilingOP">Ceiling set by Operator</label>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Insert Number" class="btn btn-chl-outline" name="submit" >
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
                        <th>Operator</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Ceiling set by Operator</th>
                        <th>Official Ceiling</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Operator</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Ceiling set by Operator</th>
                        <th>Official Ceiling</th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
{{--                        @if(isset($deplist) && count($deplist))--}}
{{--                            @php--}}
{{--                                $no= 1;--}}
{{--                            @endphp--}}
{{--                            @foreach($deplist as $d)--}}
{{--                                <tr>--}}
{{--                                    <td>{!! $no++ !!}</td>--}}
{{--                                    <td>{!! $d->dept_name !!}</td>--}}
{{--                                    <td>{!! $d->dept_code !!}</td>--}}
{{--                                    <td>@if($d->status==1) Active @else Inactive @endif</td>--}}
{{--                                    <td></td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @else--}}
{{--                            <tr>--}}
{{--                                <td colspan="5" class="text-danger text-center">Not Found!</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@stop

