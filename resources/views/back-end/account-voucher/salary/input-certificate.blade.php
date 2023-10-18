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
                    @if(auth()->user()->hasPermission('list_voucher_document'))
                        <a class="btn btn-success btn-sm" href="{{route("uploaded.voucher.list")}}"><i class="fas fa-list-check"></i> Uploaded List</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}} individual</h3>
                </div>
                <form action="{{ route('input.salary.certificate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="officer" name="officer" type="text" placeholder="Enter officer name" value="{{old('officer')}}" list="officer-list" required/>
                                <datalist id="officer-list">
                            @if(count($users))
                                @foreach($users as $user)
                                    <option value="{!! $user->name !!}">{!! $user->employee_id !!}, {!! $user->getDepartment->dept_name !!} , {!! $user->getBrance->branch_name !!} </option>
                                @endforeach
                            @endif
                                </datalist>
                                <label for="officer">Officer Name<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="from" name="from" type="month" placeholder="Enter Financial Year From" value="{{old('from')}}" required/>
                                        <label for="voucher_date">Financial Year From <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="to" name="to" type="month" placeholder="Enter Financial Year To" value="{{old('to')}}" required/>
                                        <label for="to">Financial Year To <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="basic" name="basic" type="number" placeholder="Enter basic salary" value="{{old('basic')}}" title="Basic salary amount" required/>
                                <label for="to">Basic<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="house_rent" name="house_rent" type="number" placeholder="Enter house rent" value="{{old('house_rent')}}" title="House Rent amount" required/>
                                        <label for="to">House Rent<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="conveyance" name="conveyance" type="number" placeholder="Enter conveyance" value="{{old('conveyance')}}" title="Conveyance amount" required/>
                                        <label for="to">Conveyance<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="medical" name="medical" type="number" placeholder="Enter medical amount" value="{{old('medical')}}" title="Medical allowance" required/>
                                        <label for="medical"> Medical Allow. <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" id="bonus" name="bonus" type="number" placeholder="Enter basic salary" value="{{old('bonus')}}" title="Festival bonus amount" required/>
                                        <label for="bonus">Festival Bonus<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="others" name="others" type="number" placeholder="Enter basic salary" value="{{old('others')}}"/>
                                <label for="others" >Others</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="remarks" name="remarks" > </textarea>
                                <label for="remarks" >Remarks</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating mb-3 float-end">
                                <button type="submit" class="btn btn-chl-outline"><i class="fa-regular fa-floppy-disk"></i> Save</button>
{{--                                <input type="submit" value="Save" class="btn btn-chl-outline" name="submit" >--}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">Your Added/Updated Documents List</h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
{{--                        @include('back-end.account-voucher._voucher_list')--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

