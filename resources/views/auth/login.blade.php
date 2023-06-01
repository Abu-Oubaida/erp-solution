@extends('layouts.auth.main')
@section('content')
                <div class="row justify-content-right custom-m-5">
                    <div class="col-lg-8"></div>
                    <div class="col-lg-4">
                        <div class="card border-0 rounded-lg mt-5 p-5">
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
                                <form action="{{ route('login.store') }}" method="POST">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="email" name="email" type="text" placeholder="Enter Your Email or Phone" value="{{old('email')}}"/>
                                        <label for="email">Email address/Phone Number</label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="password" type="password" placeholder="Password" name="password" value="{{old('password')}}"/>
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" name="remember" id="remember_me" type="checkbox" value="" />
                                        <label class="form-check-label" for="remember_me">Remember Password</label>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <a class="small text-chl" href="{{route('password.request')}}" >Forgot Password?</a>
                                        <button class="btn btn-primary btn-bg-chl" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center py-3">
                                <div class="small"><a href="{{route('register')}}" class="text-chl">Need an account? Sign up!</a></div>
                            </div>
                        </div>
                    </div>
                </div>

@stop
