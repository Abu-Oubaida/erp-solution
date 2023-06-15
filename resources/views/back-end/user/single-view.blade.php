@extends('layouts.back-end.main')
@section('mainContent')
    <div class="container-fluid px-4">
        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> Go Back</a>
        <h1 class="mt-4">{{str_replace('-', ' ', config('app.name'))}}</h1>
        <div class="row">
            <div class="col-md-4">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item">
                        <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="text-capitalize text-chl">Previous</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a style="text-decoration: none;" href="#" class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</a>
                    </li>
                </ol>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-8">
{{--                        <div class="float-end">--}}
{{--                            @if($com->status == 1)--}}
{{--                                {!! ($com->user_id == \Illuminate\Support\Facades\Auth::user()->id)?"<a href='".route('edit.my.complain',['complainID'=>\Illuminate\Support\Facades\Crypt::encryptString($com->id)])."' class='btn btn-primary w-auto' role='button'><i class='fas fa-edit'></i> Edit</a>":''!!}--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if(!($user))
                            <div class="row">
                                <div class="col-md-12 text-center text-danger">
                                    Not Found!
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th>User Information</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Name:</th>
                                                    <td>{!! $user->name !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Employee ID:</th>
                                                    <td>{!! $user->employee_id !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Department:</th>
                                                    <td>{!! $user->dept_name !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Access Role:</th>
                                                    <td>{!! $user->display_name !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{!! $user->email !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone:</th>
                                                    <td>{!! $user->phone !!}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6>User File Manager Permission</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control" id="per" ref="{{\Illuminate\Support\Facades\Crypt::encryptString($user->id)}}">
                                                <option value="">--Permission--</option>
                                                <option value="1">View Only</option>
                                                <option value="2">Read/Write</option>
                                                <option value="2">Read/Write/Delete</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <select class="form-control" id="dir">
                                                <option value="">--Select folder--</option>
                                                @if(@$fileManagers && count($fileManagers))
                                                    @foreach($fileManagers as $file)
                                                        <option value="{!!$file!!}">{!! $file !!}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary btn-chl" id="perAdd" type="button" onclick="return confirm('Are you sure!')"><i class="fas fa-plus"></i> Add</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Directory</th>
                                                    <th>Permission</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="f-p-list">
                                            @include("back-end.user._file-permission-list")
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12 problem-details">
                                    <span>Problem Details:</span>
{{--                                    {!! $com->details !!}--}}
                                </div>


                            </div>
                            <div class="row">

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
