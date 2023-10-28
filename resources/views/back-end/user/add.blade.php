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
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                </div>
                <form action="{{ route('add.user') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="name" name="name" type="text" placeholder="Enter full name" value="{{old('name')}}"/>
                                <label for="name">Full name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="phone" name="phone" type="number" placeholder="Enter phone number" value="{{old('phone')}}"/>
                                <label for="phone">Phone number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter email address" value="{{old('email')}}"/>
                                <label for="email">Email address <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-control text-capitalize" id="branch" name="branch">
                                    @if(isset($branches) || (count($branches) > 0))
                                        <option value="0">--Select please--</option>
                                        @foreach($branches as $b)
                                            <option value="{{$b->id}}" @if(old('branch') == $b->id) selected @endif>{{$b->branch_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="branch">Branch Name</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="to" name="dept">
                                    <option value=""></option>
                                    @if(isset($depts) || (count($depts) > 0))
                                        <option value="0">--Select please--</option>
                                        @foreach($depts as $d)
                                            <option value="{{$d->id}}" @if(old('dept') == $d->id) selected @endif>{{$d->dept_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="priority">To Department <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="designation" name="designation">
                                    <option value=""></option>
                                    @if(isset($designations) || (count($designations) > 0))
                                        <option value="0">--Select please--</option>
                                        @foreach($designations as $d)
                                            <option value="{{$d->id}}" @if(old('designation') == $d->id) selected @endif>{{$d->title}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="designation">Desingation <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <select class="form-control" id="roll" name="roll">
                                    <option value=""></option>
                                    @if(isset($roles) || (count($roles) > 0))
                                        <option value="0">--Select please--</option>
                                        @foreach($roles as $r)
                                            <option value="{{$r->id}}" @if(old('roll') == $r->id) selected @endif>{{$r->display_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="roll">User Roll <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="pass" name="password" type="password" placeholder="Enter password" value=""/>
                                <label for="password">New password <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="conform" name="password_confirmation" type="password" placeholder="Enter conform password" value=""/>
                                <label for="conform">Conform password <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating mb-3 float-end">
                                <input type="submit" value="Insert User" class="btn btn-chl-outline" name="submit" >
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

