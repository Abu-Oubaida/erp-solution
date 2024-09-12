@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm float-end"><i class="fas fa-chevron-left"></i> Go Back</a>
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
                <div class="float-end">
                    <a class="btn btn-primary btn-sm" href="{{route('blood.group.list')}}"><i class="fas fa-list-check"></i>  Blood List</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                        </div>
                        <form action="{{ route('add.blood.group') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-floating mb-4">
                                        <input class="form-control" id="blood_type" name="blood_type" type="text" placeholder="Enter Blood Group Name" value="{{old('blood_type')}}" required/>
                                        <label for="blood_type">Blood Group Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <div class="form-floating mb-3 float-end">
                                        <input type="submit" value="Insert" class="btn btn-chl-outline" name="submit" >
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="text-capitalize">List of blood group</h3>
                        <table class="table table-sm" id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Blood Group</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($bloods) && count($bloods))
                                @php
                                    $no= 1;
                                @endphp
                                @foreach($bloods as $b)
                                    <tr>
                                        <td>{!! $no++ !!}</td>
                                        <td>{!! $b->blood_type !!}</td>
                                        <td>
                                            <form action="{{route('delete.blood.group')}}" class="display-inline" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($b->id) !!}">
                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
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
@stop

