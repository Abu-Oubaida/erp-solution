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
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <h3 class="text-capitalize">{{str_replace('.', ' ', \Route::currentRouteName())}}</h3>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="datatablesSimple">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Voucher type title</th>
                                <th>Status</th>
                                <th>Code</th>
                                <th>Remarks</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Voucher type title</th>
                                <th>Status</th>
                                <th>Code</th>
                                <th>Remarks</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @if(isset($voucherTypes) && count($voucherTypes))
                                @php
                                    $no= 1;
                                @endphp
                                @foreach($voucherTypes as $vt)
                                    <tr>
                                        <td>{!! $no++ !!}</td>
                                        <td>{!! $vt->voucher_type_title !!}</td>
                                        <td>@if($vt->status ==1) Active @else Inactive @endif</td>
                                        <td>{!! $vt->code !!}</td>
                                        <td>{!! $vt->remarks !!}</td>
                                        <td>{!! $vt->createdBY->name !!}</td>
                                        <td>{!! $vt->updatedBY->name !!}</td>
                                        <td>
                                            <a href="{{route('edit.voucher.type',["voucherTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($vt->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                                            <form action="{{route('delete.voucher.type')}}" class="display-inline" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($vt->id) !!}">
                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the voucher type?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
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
        </div>
    </div>
@stop

