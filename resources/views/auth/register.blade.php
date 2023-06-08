@extends('layouts.auth.main')
@section('content')
                <div class="row justify-content-center custom-m-5">
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-lg mt-4 p-5">
                            <div class="card-header text-center">
                                <img src="{{url("image/logo/chl_logo.png")}}" alt="Credence Housing Limited" class="img-fluid" width="50%">
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show z-index-1 w-auto error-alert right-0" role="alert">
                                            @foreach ($errors->all() as $error)
                                                <div>{{$error}}</div>
                                            @endforeach
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                {{--                For Insert message Showing--}}
                                @if (session('success'))
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show z-index-1 right-0 w-auto error-alert" role="alert">
                                            <div>{{session('success')}}</div>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                {{--                For Insert message Showing--}}
                                @if (session('error'))
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show z-index-1 right-0 w-auto error-alert" role="alert">
                                            <div>{{session('error')}}</div>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                @if (session('warning'))
                                    <div class="col-12">
                                        <div class="alert alert-warning alert-dismissible fade show z-index-1 right-0 w-auto error-alert" role="alert">
                                            <div>{{session('warning')}}</div>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <form action="{{ route('store.register') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="name" name="name" type="text" placeholder="Enter Your Name" value="{{old('name')}}"/>
                                                <label for="name">Full Name <span style="color: red">*</span></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="phone" name="phone" type="number" placeholder="Enter Your Phone Number" value="{{old('phone')}}"/>
                                                <label for="phone">Phone Number <span style="color: red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="email" name="email" type="email" placeholder="Enter Your Email" value="{{old('email')}}"/>
                                                <label for="email">Email address <span style="color: red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-control" id="dept" name="branch" >
                                                    @if(isset($branches) || (count($branches) > 0))
                                                        <option value="0">--Select please--</option>
                                                        @foreach($branches as $b)
                                                            <option value="{{$b->id}}" @if(old('branch') == $b->id) selected @endif>{{$b->branch_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <label for="dept">Branch Name<span style="color: red">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <select class="form-control" id="dept" name="dept" >
                                                    @if(isset($depts) || (count($depts) > 0))
                                                        <option value="0">--Select please--</option>
                                                        @foreach($depts as $d)
                                                            <option value="{{$d->id}}" @if(old('dept') == $d->id) selected @endif>{{$d->dept_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                <label for="dept">Department<span style="color: red">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="password" name="password" type="password" placeholder="Enter New Password" value="{{old('password')}}"/>
                                                <label for="password">New Password <span style="color: red">*</span></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="conform" name="password_confirmation" type="password" placeholder="Enter Conform Password" value="{{old('password_confirmation')}}"/>
                                                <label for="conform">Confirmed Password <span style="color: red">*</span></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0 float-end">
                                        <button class="btn btn-primary btn-bg-chl" type="submit">Register</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-3">
                                <div class="small"><a href="{{route('login')}}" class="text-chl">Already registered? Login!</a></div>
                            </div>
                        </div>
                    </div>
                </div>

@stop
