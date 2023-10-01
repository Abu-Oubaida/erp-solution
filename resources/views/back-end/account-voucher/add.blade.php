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
                    <a class="btn btn-success btn-sm" href="{{"uploaded.voucher.list"}}"><i class="fas fa-list-check"></i>  Show All List</a>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                </div>
                <form action="{{ route('add.voucher.info') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="voucher_number" name="voucher_number" type="text" placeholder="Enter Voucher Number" value="{{old('voucher_number')}}"/>
                                <label for="voucher_number">Voucher Number <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <select class="form-control" name="voucher_type" id="voucher_type">
                                    <option value="">--Select Voucher Type--</option>
                                    @foreach($voucherTypes as $date)
                                        <option value="{!! $date->id !!}">{!! $date->voucher_type_title !!}</option>
                                    @endforeach
                                </select>
                                <label for="voucher_type">Voucher Type<span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="10"></textarea>
                                <label for="remarks">Remarks<span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating mb-3">
                                <input type="file" class="form-control" name="voucher_file" id="voucher_file" >
                                <label for="voucher_file">Voucher Document<span class="text-danger">*</span></label>
                                <small>.pdf/.image</small>
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

